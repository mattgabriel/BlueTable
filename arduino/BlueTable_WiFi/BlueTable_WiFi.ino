

#include <SPI.h>
#include <WiFi.h>

//Printer includes
#include "SoftwareSerial.h"
#include "Adafruit_Thermal.h"
#include <avr/pgmspace.h>
int printer_RX_Pin = 5;  // This is the green wire
int printer_TX_Pin = 6;  // This is the yellow wire
Adafruit_Thermal printer(printer_RX_Pin, printer_TX_Pin);
//Printer ends

 
//char ssid[] = "BattleHack";      //  your network SSID (name)
//char pass[] = "paypal14";   // your network password
char ssid[] = "MattGabriel";      //  your network SSID (name)
char pass[] = "matt1234";
int keyIndex = 0;            // your network key Index number (needed only for WEP)

int status = WL_IDLE_STATUS;

// Initialize the Wifi client library
WiFiClient client;

// server address:
char server[] = "104.130.141.81";
//IPAddress server(64,131,82,241);

unsigned long lastConnectionTime = 0;            // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 10L * 300L; // delay between updates, in milliseconds


//Table led
int tableLed = 4;
unsigned long blinkLastMillis = 0;
unsigned long blinkSpeed = 500;
int blinkLastState = 0;
int blinkCurrentOption = 2;

int numberOfBytesReceived = 0;
String receivedString = "";

boolean isData = false;
String receivedData;

boolean needToPrintReceipt = false;




void setup() {
  pinMode(tableLed,OUTPUT);
  updateTableStatusLed();
  
  //Initialize serial and wait for port to open:
  Serial.begin(9600);
  while (!Serial) {
    ; // wait for serial port to connect. Needed for Leonardo only
  }

  // check for the presence of the shield:
  if (WiFi.status() == WL_NO_SHIELD) {
    Serial.println("WiFi shield not present");
    // don't continue:
    while (true);
  }
  
  String fv = WiFi.firmwareVersion();
  if ( fv != "1.1.0" )
    Serial.println("Please upgrade the firmware");

  // attempt to connect to Wifi network:
  while ( status != WL_CONNECTED) {
    Serial.print("Attempting to connect to SSID: ");
    Serial.println(ssid);
    // Connect to WPA/WPA2 network. Change this line if using open or WEP network:
    status = WiFi.begin(ssid, pass);
    // wait 10 seconds for connection:
    delay(6000);
  }
  // you're connected now, so print out the status:
  printWifiStatus();
}



void loop() {
  updateTableStatusLed();
  
  if(needToPrintReceipt){
    printReceipt();
    needToPrintReceipt = false;
  }
  
  
  // if there's incoming data from the net connection.
  // send it out the serial port. This is for debugging
  // purposes only:
  while (client.available()) {
    char c = client.read();
    receivedData = receivedData + c;
    // Delete HTTP headers
    if(receivedData.endsWith("Content-Type: application/json")){
      receivedData = "";
    }
    isData = true;
  }
  
  if(isData){
   receivedData.trim();
   int myData =  receivedData.toInt();
   //Serial.println(receivedData); 
   isData = false;
   if(myData > blinkCurrentOption || myData == 0)
   {
        switch(myData) {
        case 0:
            blinkCurrentOption = 0;
            Serial.println("Table available");
            break;
        case 1:
            blinkCurrentOption = 1;
            Serial.println("Table taken");
            break;
        case 2:
            blinkCurrentOption = 1;
            Serial.println("Drinks served");
            break;
        case 3:
            blinkCurrentOption = 1;
            Serial.println("Food served");
            break;
        case 4:
            blinkCurrentOption = 1;
            Serial.println("Dessert served");
            break;
        case 5:
            blinkCurrentOption = 2;
            Serial.println("Payed");
            needToPrintReceipt = true;
            break;
        case 6:
            blinkCurrentOption = 2;
            Serial.println("Awaiting cleaning");
            needToPrintReceipt = false;
            break;
        }
    }
  }

  // if ten seconds have passed since your last connection,
  // then connect again and send data:
  if (millis() - lastConnectionTime > postingInterval) {
    httpRequest();
  }

}

// this method makes a HTTP connection to the server:
void httpRequest() {
  // close any connection before send a new request.
  // This will free the socket on the WiFi shield
  client.stop();

  // if there's a successful connection:
  if (client.connect(server, 80)) {
    Serial.println("connecting...");
    // send the HTTP PUT request:
    client.println("GET /api/table/status/table14 HTTP/1.1");
    client.println("Host: www.104.130.141.81");
    client.println("Connection: close");
    client.println();

    // note the time that the connection was made:
    lastConnectionTime = millis();
  }
  else {
    // if you couldn't make a connection:
    Serial.println("connection failed");
  }
}


void printWifiStatus() {
  // print the SSID of the network you're attached to:
  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());

  // print your WiFi shield's IP address:
  IPAddress ip = WiFi.localIP();
  Serial.print("IP Address: ");
  Serial.println(ip);

  // print the received signal strength:
  long rssi = WiFi.RSSI();
  Serial.print("signal strength (RSSI):");
  Serial.print(rssi);
  Serial.println(" dBm");
}



void updateTableStatusLed(){
  switch (blinkCurrentOption) {
    case 2: //on (blink it)
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
    case 1: //on
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
  printer.println("11th October 2014 at 14:34");
  
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

