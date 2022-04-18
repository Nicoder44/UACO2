#include <LiquidCrystal_I2C.h>

#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

#define T 5

LiquidCrystal_I2C lcd(0x27,16,2);

void setup()
{
  srand(time(NULL));
  lcd.init();
  lcd.init();

  lcd.backlight();
  lcd.setCursor(0,0);
  lcd.print("temp:");

  lcd.setCursor(0,1);
  lcd.print("ppm:");

  lcd.setCursor(8,1);
  lcd.print("hmdt:");
}

void loop()
{
  lcd.setCursor(0,0);
  lcd.print("temp:");

  lcd.setCursor(0,1);
  lcd.print("ppm:");

  lcd.setCursor(8,1);
  lcd.print("hmdt:");
  delay(5000);
  lcd.clear();
  int temp, ppm, humidite;
  temp = rand()%101;
  ppm = rand()%101;
  humidite = rand()%101;
  lcd.setCursor(6,0);
  lcd.print(temp);
  lcd.setCursor(5,1);
  lcd.print(ppm); 
  lcd.setCursor(14,1);
  lcd.print(humidite);  
}
