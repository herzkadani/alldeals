#!/usr/bin/python3

import requests
import json
import re
from html2text import html2text
from bs4 import BeautifulSoup
from multiprocessing import Pool
from random import randint
import time
import pprint
from math import floor

def get_deal(url):
    """
    Get front page deal from a given url
    """
    deal = {}
    r = requests.get(url)
    soup = BeautifulSoup(r.text, 'html.parser')
    deal["url"] = url
    deal["title"] = soup.find('h1', {'class': 'product-description__title1'}).text
    deal["subtitle"] = soup.find('h2', {'class': 'product-description__title2'}).text
    deal["new_price"] = soup.find('h2', {'class': 'product-pricing__prices-new-price'}).text.replace("CHF ","").replace("\u2013","-")
    try:
        oldprice = soup.find('strong', {'class': 'product-pricing__prices-old-price'}).text
        # match regex
        match = re.finditer(r"([0-9]+\.([0-9]{2}|–))", oldprice)
        for m in match:
            deal["old_price"] = m.group(0)

        #oldprice = oldprice.replace("\n","").replace("statt CHF ","").replace(" ","").replace(".\u2013","")
        # remove trailing 2
    except:
        deal["old_price"] = "?"
    deal["availability"] = soup.find('strong', {'class': 'product-progress__availability'}).text
    deal["image"] = soup.find('img', {'class': 'product-img-main-pic'}).get('src')
    # Add timestamp
    deal["timestamp"] = int(round(time.time() * 1000))
    return deal

def get_digitec_deal(url):
    """
    Get the digitec deal from a given url
    """
    deal = {}
    r = requests.get(url)
    soup = BeautifulSoup(r.text, 'html.parser')
    deal["url"] = url
    deal["subtitle"] = soup.find('div', {'class': 'sc-j0oacw-0'}).text
    #deal["apidata"]= json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])['props']['apolloState']
    apidata = json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])['props']['apolloState']
    apidata_keys = dict(enumerate(apidata))

    deal["image"] = apidata[apidata_keys[0]]["images"][0]["url"]
    deal["title"] = apidata[apidata_keys[0]]["productTypeName"]
    deal["availability"] = str(floor(100 - apidata[apidata_keys[1]]["salesInformation"]["numberOfItemsSold"] / apidata[apidata_keys[1]]["salesInformation"]["numberOfItems"] * 100))+"%"
    deal["new_price"] = soup.find('span', {'class': 'sc-15boyr7-0'}).find('strong').text.replace('.\u2013','')
    deal["old_price"] = soup.find('span', {'class': 'sc-15boyr7-0'}).find('span').text.replace('statt ', '').replace('.\u2013','')
    match = re.finditer(r"([0-9]+\.([0-9]{2}|–))", deal["old_price"])
    for m in match:
        deal["old_price"] = m.group(0)

    if not "." in deal["new_price"]:
        deal["new_price"] = deal["new_price"] + ".-"
    if not "." in deal["old_price"]:
        deal["old_price"] = deal["old_price"] + ".-"
    #deal["raw"] = json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])
    # Add timestamp
    deal["timestamp"] = int(round(time.time() * 1000))
    return deal

def get_zmin_deal(url):
    deal = {}
    r = requests.get(url)
    soup = BeautifulSoup(r.text, 'html.parser')
    deal["title"] = soup.find('h1', {'class': 'deal-title'}).text
    deal["subtitle"] = soup.find('h2', {'class': 'deal-coupon__subtitle'}).text
    deal["availability"] = soup.find('div', {'class': ''}).text

def get_mediamarkt_deal(url):
    deal = {}
    r = requests.get(url)
    soup = BeautifulSoup(r.text, 'html.parser')
    product_url = soup.find('div', {'class': 'tagesdeal-outer'}).parent['href']

    r = requests.get(f"{url}{product_url}")
    soup = BeautifulSoup(r.text, 'html.parser')

    deal["image"] = soup.find('a', {'class': 'zoom'})['href']

    titles = soup.find('h1', {'itemprop':'name'}).getText().split(" - ")
    deal["title"] = titles[0]
    deal["subtitle"] = titles[1]
    deal["url"] = f"{url}{product_url}"
    deal["timestamp"] = int(round(time.time() * 1000))
    deal["availability"] = "Nur solange Vorrat"
    deal["old_price"] = soup.find('div', {'class': 'price-old'}).text
    deal["new_price"] = soup.find('div', {'class': 'price'}).text

    return deal

def get_any_deal(deal):
    """
    Return deal by string
    """
    #time.sleep(randint(1,120))
    try:
        if deal == "digitec":
            return get_digitec_deal("https://digitec.ch/de/liveshopping/81")
        elif deal == "galaxus":
            return get_digitec_deal("https://galaxus.ch/de/liveshopping/81")
        elif deal == "daydeal_daily":
            return get_deal("https://www.daydeal.ch/")
        elif deal == "daydeal_weekly":
            return get_deal("https://www.daydeal.ch/deal-of-the-week/")
        elif deal == "mediamarkt":
            return get_mediamarkt_deal("https://www.mediamarkt.ch")
        else:
            return get_deal("https://www.blickdeal.ch/")
    except Exception as e:
        return []
with Pool(5) as p:
    deals = p.map(get_any_deal, ["digitec", "galaxus", "daydeal_daily", "daydeal_weekly", "blick", "mediamarkt"])
output = {}
output["digitec"] = deals[0]
output["galaxus"] = deals[1]
output["daydeal_daily"] = deals[2]
output["daydeal_weekly"] = deals[3]
output["blickdeal"] = deals[4]
output["mediamarkt"] = deals[5]
with open("/var/www/alldeals/frontend/deals.json", "w") as f:
    f.write(json.dumps(output))
