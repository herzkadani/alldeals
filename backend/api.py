#!/usr/bin/python3
# pylint: disable=W0702,C0301,C0116,C0114,R0911,broad-except
import json
import re
import time
from datetime import date
from math import floor
from multiprocessing import Pool

import requests
from bs4 import BeautifulSoup

from pprint import pprint


def get_deal(url):
    """
    Get front page deal from a given url
    """
    print("getting deal")
    deal = {}
    request = requests.get(url, timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")
    deal["url"] = url
    deal["title"] = soup.find("h1", {"class": "product-description__title1"}).text
    deal["subtitle"] = soup.find("h2", {"class": "product-description__title2"}).text
    deal["new_price"] = (
        soup.find("h2", {"class": "product-pricing__prices-new-price"})
        .text.replace("CHF ", "")
        .replace("\u2013", "-")
    )
    try:
        oldprice = soup.find(
            "strong", {"class": "product-pricing__prices-old-price"}
        ).text
        # match regex
        matches = re.finditer(r"([0-9]+\.([0-9]{2}|–))", oldprice)
        for match in matches:
            deal["old_price"] = match.group(0)

        # oldprice = oldprice.replace("\n","").replace("statt CHF ","").replace(" ","").replace(".\u2013","")
        # remove trailing 2
    except:
        deal["old_price"] = "?"
    deal["availability"] = soup.find(
        "strong", {"class": "product-progress__availability"}
    ).text
    deal["image"] = soup.find("img", {"class": "product-img-main-pic"}).get("src")
    # Add timestamp
    deal["timestamp"] = int(round(time.time() * 1000))
    return deal


def get_digitec_deal(url):
    """
    Get the digitec deal from a given url
    """
    print("getting digiec deal")
    deal = {}
    request = requests.get(url, timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")
    deal["url"] = url
    #deal["subtitle"] = soup.find("div", {"class": "sc-pudwgx-6"}).text
    # deal["apidata"]= json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])['props']['apolloState']
    #apidata = json.loads(soup.find("script", {"id": "__NEXT_DATA__"}).contents[0])[
    #    "props"
    #]["pageProps"]["products"]
    apidata_raw = json.loads(soup.find("script", {"id": "__NEXT_DATA__"}).text)["props"]["apolloState"]["grapholit"]
    # apidata_keys = dict(enumerate(apidata))
    apidata = []

    for key in apidata_raw.keys():
        apidata.append(apidata_raw[key])
    deal["image"] = apidata[0]["images"][0]["url"]
    deal["title"] = apidata[0]["name"]
    deal["subtitle"] = apidata[0]["productTypeName"]
    deal["availability"] = (
        str(
            floor(
                100
                - apidata[1]["salesInformation"]["numberOfItemsSold"]
                / apidata[1]["salesInformation"]["numberOfItems"]
                * 100
            )
        )
        + "%"
    )
    #deal["new_price"] = soup.find("span", {"class": "sc-pr6hlf-1"}).text.replace(
    #    ".\u2013", ""
    #)
    deal["new_price"] = apidata[1]["price"]["amountIncl"]
    try:
        #deal["old_price"] = (
        #    soup.find("span", {"class": "sc-pr6hlf-2"})
        #    .text.replace("statt ", "")
        #    .replace(".\u2013", "")
        #)
        #if "cash" in deal["old_price"].lower():
        #    deal["old_price"] = "??"
        deal["old_price"] = apidata[1]["price"]["insteadOfPrice"]
    except:
        deal["old_price"] = "??"
    #matches = re.finditer(r"([0-9?]+\.([0-9?]{2}|–))", deal["old_price"])
    #for match in matches:
    #    deal["old_price"] = match.group(0)

    #if not "." in deal["new_price"]:
    #    deal["new_price"] = deal["new_price"] + ".-"
    #if not "." in deal["old_price"]:
    #    deal["old_price"] = deal["old_price"] + ".-"
    # deal["raw"] = json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])
    # Add timestamp
    deal["timestamp"] = int(round(time.time() * 1000))
    return deal


def get_zmin_deal(url):
    deal = {}
    request = requests.get(url, timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")
    deal["title"] = soup.find("h3", {"class": "deal-title"}).text
    deal["subtitle"] = soup.find("p", {"class": "deal-subtitle"}).text
    deal["availability"] = soup.find("span", {"class": "deal-inventory"}).text + "%"
    deal["image"] = soup.find("div", {"class": "deal-img"}).find("img").get("data-src")
    deal["url"] = url
    deal["timestamp"] = int(round(time.time() * 1000))
    deal["new_price"] = soup.find("h1", {"class": "deal-price"}).text
    try:
        deal["old_price"] = (
            soup.find("div", {"class": "deal-old-price"}).find("span").text
        )
    except:
        deal["old_price"] = "??.??"
    return deal


def get_mediamarkt_deal(url):
    deal = {}
    request = requests.get(url, timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")
    product_url = soup.find("div", {"class": "tagesdeal-outer"}).parent["href"]

    request = requests.get(f"{url}{product_url}", timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")

    deal["image"] = soup.find("a", {"class": "zoom"})["href"]

    titles = soup.find("h1", {"itemprop": "name"}).getText().split(" - ")
    deal["title"] = titles[0]
    deal["subtitle"] = titles[1]
    deal["url"] = f"{url}{product_url}"
    deal["timestamp"] = int(round(time.time() * 1000))
    deal["availability"] = "Nur solange Vorrat"
    deal["old_price"] = soup.find("div", {"class": "price-old"}).text
    deal["new_price"] = soup.find("div", {"class": "price"}).text

    return deal


def get_any_deal(deal):
    """
    Return deal by string
    """
    # time.sleep(randint(1,120))
    try:
        if deal == "digitec":
            return get_digitec_deal("https://www.digitec.ch/de/adventcalendar")
        if deal == "galaxus":
            return get_digitec_deal("https://www.galaxus.ch/de/adventcalendar")
        if deal == "daydeal_daily":
            return get_deal("https://www.daydeal.ch/")
        if deal == "daydeal_weekly":
            return get_deal("https://www.daydeal.ch/deal-of-the-week/")
        if deal == "mediamarkt":
            return get_mediamarkt_deal("https://www.mediamarkt.ch")
        if deal == "zmin":
            return get_zmin_deal("https://myshop.20min.ch/de_DE")
        return get_deal("https://www.blickdeal.ch/")
    except Exception as ex:
        print(f"Failed to parse {deal}")
        print(f"The following exception was thrown:\n{ex}")
        return []


with Pool(5) as p:
    deals = p.map(
        get_any_deal,
        [
            "digitec",
            "galaxus",
            "daydeal_daily",
            "daydeal_weekly",
            "blick",
            "mediamarkt",
            "zmin",
        ],
    )
output = {}
output["digitec"] = deals[0]
output["galaxus"] = deals[1]
output["daydeal_daily"] = deals[2]
output["daydeal_weekly"] = deals[3]
output["blickdeal"] = deals[4]
output["mediamarkt"] = deals[5]
output["zmin"] = deals[6]
print("done, writing file")
filename_date = date.today().strftime("%Y-%m-%d")
with open("/deals/deals-" + filename_date + ".json", "w", encoding="utf-8") as f:
    f.write(json.dumps(output))
