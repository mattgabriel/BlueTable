
#include "SoftwareSerial.h"
#include "Adafruit_Thermal.h"
#include <avr/pgmspace.h>

int printer_RX_Pin = 5;  // This is the green wire
int printer_TX_Pin = 6;  // This is the yellow wire

Adafruit_Thermal printer(printer_RX_Pin, printer_TX_Pin);

void setup(){
  Serial.begin(9600);
  pinMode(7, OUTPUT); digitalWrite(7, LOW); // To also work w/IoTP printer
  printer.begin();

  printer.setSize('L');     // Set type size, accepts 'S', 'M', 'L'
  printer.println("Your Bill"); // Print line

  // Set text justification (right, center, left) -- accepts 'L', 'C', 'R'
  printer.setSize('M');
  printer.justify('L');
  printer.println("@ Battlehack");
  printer.justify('R');
  printer.setSize('S');
  printer.println("7th October 2014 at 20:34");
  
  printer.println("");
  printer.println("");
  printer.println("");
  
  
  printer.justify('L');
  printer.println("x2 Orange juice..... $8.49");
  printer.println("Veggie pizza........ $13.99");
  printer.println("Total............... $22.48");
  
  printer.println("");
  printer.println("");
  
  printer.justify('C');
  printer.println("Thank you, come again.");
  
  printer.println("");
  printer.println("");
  
  printer.sleep();      // Tell printer to sleep
  printer.wake();       // MUST call wake() before printing again, even if reset
  printer.setDefault(); // Restore printer to defaults
}

void loop() {
}
