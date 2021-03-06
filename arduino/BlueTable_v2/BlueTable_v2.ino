#include <SPI.h>
#include "Adafruit_BLE_UART.h"

//Printer includes
#include "SoftwareSerial.h"
#include "Adafruit_Thermal.h"
#include <avr/pgmspace.h>
int printer_RX_Pin = 5;  // This is the green wire
int printer_TX_Pin = 6;  // This is the yellow wire
Adafruit_Thermal printer(printer_RX_Pin, printer_TX_Pin);
//Printer ends

String tableId = "table14";

#define ADAFRUITBLE_REQ 10
#define ADAFRUITBLE_RDY 2     // This should be an interrupt pin (#2 or #3)
#define ADAFRUITBLE_RST 9
Adafruit_BLE_UART BTLEserial = Adafruit_BLE_UART(ADAFRUITBLE_REQ, ADAFRUITBLE_RDY, ADAFRUITBLE_RST);

int led1 = 4;
int led2 = 7;

//Table led
int tableLed = 8;
unsigned long blinkLastMillis = 0;
unsigned long blinkSpeed = 500;
int blinkLastState = 0;
int blinkCurrentOption = 0;

int numberOfBytesReceived = 0;
String receivedString = "";

void setup(void) { 
  Serial.begin(9600);
  while(!Serial); // Leonardo/Micro should wait for serial init
  Serial.println(F("Adafruit Bluefruit Low Energy nRF8001 Print echo demo"));

  BTLEserial.setDeviceName("B.Table"); /* 7 characters max! */
  
  pinMode(led1, OUTPUT);
  pinMode(led2, OUTPUT);
  pinMode(tableLed, OUTPUT);

  BTLEserial.begin();
}

/**************************************************************************/
/*    Constantly checks for new events on the nRF8001
/**************************************************************************/
aci_evt_opcode_t laststatus = ACI_EVT_DISCONNECTED;

void loop(){
  // Tell the nRF8001 to do whatever it should be working on.
  BTLEserial.pollACI();

  // check current status
  aci_evt_opcode_t status = BTLEserial.getState();
  // If the status changed....
  if (status != laststatus) {
    statusActions(status);
    // Set the last status change to this one
    laststatus = status;
  }

  if (status == ACI_EVT_CONNECTED) {
    // Check if there's any data being sent to us
    receiveData();
    // check if we need to send data
    sendData();
  }
  
  updateTableStatusLed();
    
}

void receiveData(){
 if (BTLEserial.available()) {
    numberOfBytesReceived = BTLEserial.available();
  }
  // OK while we still have something to read, get a character and print it out
  if(numberOfBytesReceived > 0){
    int i = 0;
    while (BTLEserial.available()) {
      char c = BTLEserial.read();
      receivedString = receivedString + c;
      i++;
      if(i == numberOfBytesReceived){
        //Serial.println(c);
        numberOfBytesReceived = 0;
        Serial.println(receivedString);
        receivedString = "";
      } 
    }
  } 
}


void sendTableId(){
  // We need to convert the line to bytes, no more than 20 at this time
  uint8_t sendbuffer[20];
  tableId.getBytes(sendbuffer, 20);
  char sendbuffersize = min(20, tableId.length());

  Serial.println("Sending TableId..."); //data: (char *)sendbuffer

  // write the data
  BTLEserial.write(sendbuffer, sendbuffersize);
}


void sendData(){
  // Check if we need to send any data (at the moment it's looking from data entered in the console
  // But it would be done via wifi, button press...
  if (Serial.available()) {
    // Read a line from Serial
    Serial.setTimeout(100); // 100 millisecond timeout
    String s = Serial.readString();

    // We need to convert the line to bytes, no more than 20 at this time
    uint8_t sendbuffer[20];
    s.getBytes(sendbuffer, 20);
    char sendbuffersize = min(20, s.length());

    Serial.println("Sending data..."); //data: (char *)sendbuffer

    // write the data
    BTLEserial.write(sendbuffer, sendbuffersize);
  }
}

void statusActions(aci_evt_opcode_t status){
  // print it out!
  if (status == ACI_EVT_DEVICE_STARTED) {
      Serial.println(F("* Advertising started"));
      blinkCurrentOption = 1;
      led(1);
  } 
  if (status == ACI_EVT_CONNECTED) {
      Serial.println(F("* Connected!"));
      blinkCurrentOption = 2;
      led(2);
      //send table id
      sendTableId();
  }
  if (status == ACI_EVT_DISCONNECTED) {
      Serial.println(F("* Disconnected or advertising timed out"));
      blinkCurrentOption = 0;
  } 
}

void led(int type){
 switch (type) {
    case 1: //green
      digitalWrite(led1, HIGH);
      digitalWrite(led2, LOW);
      break;
    case 2: //red
      digitalWrite(led1, LOW);
      digitalWrite(led2, HIGH);
      break;
    case 3: //all
      digitalWrite(led1, HIGH);
      digitalWrite(led2, HIGH);
      break;
    case 4: //none/off
      digitalWrite(led1, LOW);
      digitalWrite(led2, LOW);
      break;
  } 
}

void updateTableStatusLed(){
  switch (blinkCurrentOption) {
    case 1: //on (blink it)
      if(millis() > (blinkLastMillis + blinkSpeed)){
        //change state
        blinkLastMillis = millis();
        if(blinkLastState == 0){
          blinkLastState = 1;
        } else {
          blinkLastState = 0;
        }
      }
      if(blinkLastState == 0){
        digitalWrite(tableLed, LOW);
      } else {
        digitalWrite(tableLed, HIGH);
      }
      break;
    case 0: //off
      digitalWrite(tableLed, LOW);
      break;
    case 2: //on
      digitalWrite(tableLed, HIGH);
      break;
  }
}


void printReceipt(){
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
