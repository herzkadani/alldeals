#!/usr/bin/python3

import requests
import json
from html2text import html2text
from bs4 import BeautifulSoup
from multiprocessing import Pool

def get_deal(url):
    """
    Get front page deal from a given url
    """
    deal = {}
    r = requests.get(url)
    soup = BeautifulSoup(r.text, 'html.parser')
    deal["title"] = soup.find('h1', {'class': 'product-description__title1'}).text
    deal["subtitle"] = soup.find('h2', {'class': 'product-description__title2'}).text
    deal["short_description"] = soup.find('ul', {'class': 'product-description__list'}).text
    deal["new_price"] = soup.find('h2', {'class': 'product-pricing__prices-new-price'}).text.replace("CHF ","").replace(".\u2013","")
    oldprice = soup.find('strong', {'class': 'product-pricing__prices-old-price'}).text
    if oldprice[-1] == "2":
        deal["old_price"] = oldprice[:-1]
    oldprice = oldprice.replace("\n","").replace("statt CHF ","").replace(" ","").replace(".\u20132","")
    deal["old_price"] = oldprice
    deal["availability"] = soup.find('strong', {'class': 'product-progress__availability'}).text
    deal["image"] = soup.find('img', {'class': 'product-img-main-pic'}).get('src')
    return deal

def get_digitec_deal(url):
    """
    Get the digitec deal from a given url
    """
    deal = {}
    r = requests.get(url)
    soup = BeautifulSoup(r.text, 'html.parser')
    deal["category"] = soup.find('a', {'class': 'sc-1xxwfxa-0'}).text
    deal["product"] = soup.find('div', {'class': 'sc-j0oacw-0'}).text
    deal["apidata"]= json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])['props']['apolloState']
    deal["status"] = soup.find('div', {'class': 'salesInformationView_stockRemaining__tDSt7'}).text.replace("\xa0"," ")
    deal["new_price"] = soup.find('span', {'class': 'sc-15boyr7-0'}).find('strong').text.replace('.\u2013','')
    deal["old_price"] = soup.find('span', {'class': 'sc-15boyr7-0'}).find('span').text.replace('statt ', '').replace('.\u2013','')
    #deal["raw"] = json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])
    return deal

def get_any_deal(deal):
    """
    Return deal by string
    """
    if deal == "digitec":
        return get_digitec_deal("https://digitec.ch/de/liveshopping/81")
    elif deal == "daydeal_daily":
        return get_deal("https://www.daydeal.ch/")
    elif deal == "daydeal_weekly":
        return get_deal("https://www.daydeal.ch/deal-of-the-week/")
    else:
        return get_deal("https://www.blickdeal.ch/")
with Pool(5) as p:
    deals = p.map(get_any_deal, ["digitec", "daydeal_daily", "daydeal_weekly", "blick"])
output = {}
output["digitec"] = deals[0]
output["daydeal_daily"] = deals[1]
output["daydeal_weekly"] = deals[2]
output["blickdeal"] = deals[3]
print(json.dumps(output))
