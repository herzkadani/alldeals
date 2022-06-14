# AllDeals - Alle Tagesangebote kombiniert
---

AllDeals is an overview page for the daily deals of some Swiss online shops.
## Demo Instance
A demo instance is hosted at [deals.gk.wtf](https://deals.gk.wtf).

## Deployment
* Clone this repository to a server
* Put the `frontend` folder into the www root
* Create a cronjob to run the backend script every few minutes
* Adjust the json output path in frontend and backend

## Q&A
**How does this work?**  
Our scripts run every few minutes, scrape the data from the websites and parse it to display on our page.  
  
**Can you add xyz?**  
Possible, however difficulty in parsing differs from site to site. Please create an [issue](https://github.com/herzkadani/alldeals/issues/new).  

**I'm responsible for one of the stores and want you to remove us.**  
Please create an [issue](https://github.com/herzkadani/alldeals/issues/new) and we'll remove it ASAP.