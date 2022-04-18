#include <stdio.h>
#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <stdlib.h>
#include <time.h>
#include <Wire.h>

const char* ssid = "Redmi";
const char* password = "Redmiiii";
const char* host = "nitoco.u-angers.fr";

int temp = 0;
int hmdt = 0;
int ppm = 0;

void setup() 
{
  Serial.begin(9600);
  srand(time(NULL));
  Serial.print("Tentative de connexion à ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }

  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() 
{
  for(int i=0; i<100;i++)
  {
    temp += rand()%32;
    hmdt += rand()%35 + 25;
    ppm += rand()%1000 + 350;
  }
  temp /= 100;
  ppm /= 100;
  hmdt /= 100;

  Serial.print("temp :");
  Serial.print(temp);
  Serial.print("\n");
  Serial.print("hmdt :");
  Serial.print(hmdt);
  Serial.print("\n");
  Serial.print("ppm :");
  Serial.print(ppm);
  Serial.print("\n");
  delay(10000);

  Serial.print("connecting to ");
  Serial.println(host);

  WiFiClientSecure client;
  const int httpPort = 443;
  if (!client.connect(host, httpPort))
  {
    Serial.println("echec de la connexion");
    return;
  }
  client.print(String("GET https://nitoco.u-angers.fr/NicoTest/InsertDataBase.php?") + 
                          ("&temperature=") + temp +
                          ("&humidity=") + hmdt +
                          ("&ppm=") + ppm + 
                          " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    unsigned long timeout = millis();
    while (client.available() == 0) {
        if (millis() - timeout > 1000) {
            Serial.println(">>> Client Timeout !");
            client.stop();
            return;
        }
    }
  /*client.print(String("GET http://nitoco.u-angers.fr/NicoTest/InsertDataBase.php?") + ("&temperature=") + temp + ("&humidity=") + hmdt + ("&ppm=") + ppm + " HTTP/1.1\r\n" + "Host: " + host + "\r\n" + "Connection: close\r\n\r\n");

  unsigned long timeout = millis();
  while(client.available() == 0)
  {
    if(millis() - timeout > 1000)
     {
      Serial.println(">>> Client Timeout !");
      client.stop();
      return;
     }
  }*/

  while(client.available())
  {
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }

  Serial.println("closing connection");
  
}
