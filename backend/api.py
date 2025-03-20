#!/usr/bin/python3
# pylint: disable=W0702,C0115,C0301,C0116,C0114,R0911,R0903,R0902,R0912,broad-except
import json
import logging
import re
import time
from datetime import date
from math import floor
from multiprocessing import Pool, freeze_support

import requests
from bs4 import BeautifulSoup


class Deal:
    def __init__(self):
        self.url = ""
        self.title = ""
        self.subtitle = ""
        self.new_price = ""
        self.old_price = ""
        self.availability = ""
        self.image = ""
        self.color = ""
        self.subcategory = ""
        self.timestamp = int(round(time.time() * 1000))

    def __repr__(self) -> str:
        return json.dumps(self.__dict__)


def get_daydealbeta(category):
    """
    Gets the deal of a daydeal beta category
    """
    base_url = "https://daydeal.ch/de/category/"
    deal_url = base_url + category

    deal = Deal()
    data = requests.get(deal_url, timeout=30)
    soup = BeautifulSoup(data.text, "html.parser")
    deal.url = deal_url
    deal.color = "#3FAA35"
    deal.subcategory = {
        "tagesdeal": "Tagesdeal",
        "it-multimedia": "IT / Multimedia",
        "haushalt-wohnen": "Haushalt / Wohnen",
        "supermarkt": "Supermarkt / Drogerie",
        "familie-baby": "Familie / Baby",
        "baumarkt-hobby": "Baumarkt / Hobby",
        "sport-freizeit": "Sport / Freizeit",
        "supermarkt-drogerie": "Supermarkt / Drogerie",
    }.get(category)
    deal.title = soup.find("h1", {"class": "ProductMain-Title"}).text
    deal.subtitle = soup.find("h2", {"class": "ProductMain-Subtitle"}).text
    deal.new_price = (
        soup.find("div", {"class": "DealPage-ProductMain"})
        .find("div", {"class": "Price-Price"})
        .text
    )
    try:
        deal.old_price = (
            soup.find("div", {"class": "DealPage-ProductMain"})
            .find("div", {"class": "Price-OldPriceValue"})
            .text
        )
    except:
        deal.old_price = "??.??"
    deal.image = soup.find("img", {"class": "ProductMain-Image"}).get("src")
    try:
        deal.availability = soup.find("span", {"class": "ProgressBar-TextValue"}).text
    except:
        deal.availability = "0%"
    return deal


def get_deal(url):
    """
    Get front page deal from a given url
    """
    deal = Deal()
    request = requests.get(url, timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")
    deal.url = url
    deal.title = soup.find("h1", {"class": "product-description__title1"}).text
    deal.subtitle = soup.find("h2", {"class": "product-description__title2"}).text
    deal.new_price = (
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
            deal.old_price = match.group(0)

        # oldprice = oldprice.replace("\n","").replace("statt CHF ","").replace(" ","").replace(".\u2013","")
        # remove trailing 2
    except:
        deal.old_price = "?"
    deal.availability = soup.find(
        "strong", {"class": "product-progress__availability"}
    ).text
    deal.image = soup.find("img", {"class": "product-img-main-pic"}).get("src")
    return deal


def get_blick_deal(name):
    """
    Get blick deal from a given url
    """
    deal = Deal()
    request = requests.get("https://box.blick.ch/deals", timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")
    deal.url = "https://box.blick.ch/deals"
    deal.color = "#E20000"
    deal.subcategory = {
        "tagesdeal": "Tagesangebot",
        "wochendeal": "Wochenangebot",
    }.get(name)
    base_deal_url = (
        soup.find("div", {"id": name}).find("a", {"class": "deal__link"}).get("href")
    )
    base_deal = BeautifulSoup(
        requests.get(base_deal_url, timeout=30).text, "html.parser"
    )
    deal.title = base_deal.find("span", {"class": "deal__name"}).text
    deal.subtitle = base_deal.find("div", {"class": "deal__description"}).find("p").text
    deal.new_price = (
        base_deal.find("span", {"class": "deal__price"})
        .text.replace("CHF ", "")
        .replace("\u2013", "-")
    )
    try:
        oldprice = base_deal.find("span", {"class": "deal__info"}).text
        # match regex
        matches = re.finditer(r"([0-9]?'?[0-9]+\.([0-9]{2}|–))", oldprice)
        for match in matches:
            deal.old_price = match.group(0)

        # oldprice = oldprice.replace("\n","").replace("statt CHF ","").replace(" ","").replace(".\u2013","")
        # remove trailing 2
    except:
        deal.old_price = "?"
    deal.availability = base_deal.find("span", {"class": "dealstripe__amount"}).text
    deal.image = base_deal.find("img", {"class": "slider__image"}).get("data-src")
    return deal


def get_digitec_deal(url, color="#005598"):
    """
    Get the digitec deal from a given url
    """
    deal = Deal()
    request = requests.get(url, timeout=30)
    soup = BeautifulSoup(request.text, "html.parser")
    deal.url = url
    deal.color = color
    deal.subcategory = "Tagesdeal"
    # deal["subtitle"] = soup.find("div", {"class": "sc-pudwgx-6"}).text
    # deal["apidata"]= json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])['props']['apolloState']
    # apidata_raw = json.loads(soup.find("script", {"id": "__NEXT_DATA__"}).contents[0])[
    #    "props"
    # ]["pageProps"]["products"]
    apidata = json.loads(soup.find("script", {"id": "__NEXT_DATA__"}).text)["props"][
        "pageProps"
    ]["preloadedQuery"]["response"]["data"]["dailyDealProducts"][0]["product"]

    deal.image = (
        "https://static01.galaxus.com"
        + apidata["previewImages"]["nodes"][0]["relativeUrl"]
        + "_720.jpeg"
    )
    deal.subtitle = apidata["nameExtensions"]["properties"]
    deal.title = apidata["name"]
    deal.availability = (
        str(
            floor(
                100
                - apidata["salesInformation"]["numberOfItemsSold"]
                / apidata["salesInformation"]["numberOfItems"]
                * 100
            )
        )
        + "%"
    )
    # deal["new_price"] = soup.find("span", {"class": "sc-pr6hlf-1"}).text.replace(
    #    ".\u2013", ""
    # )
    deal.new_price = apidata["price"]["amountInclusive"]
    try:
        # deal["old_price"] = (
        #    soup.find("span", {"class": "sc-pr6hlf-1"})
        #    .text.replace("statt ", "")
        #    .replace(".\u2013", "")
        # )
        # if "cash" in deal["old_price"].lower():
        #    deal["old_price"] = "??"
        deal.old_price = apidata["insteadOfPrice"]["price"]["amountInclusive"]
    except:
        deal.old_price = "??"
    # matches = re.finditer(r"([0-9?]+\.([0-9?]{2}|–))", deal["old_price"])
    # for match in matches:
    #    deal["old_price"] = match.group(0)

    # if not "." in deal["new_price"]:
    #    deal["new_price"] = deal["new_price"] + ".-"
    # if not "." in deal["old_price"]:
    #    deal["old_price"] = deal["old_price"] + ".-"
    # deal["raw"] = json.loads(soup.find('script', {'id':'__NEXT_DATA__'}).contents[0])
    return deal


def get_mediamarkt_deal(url):
    deal = Deal()
    fake_ua_headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3"
    }
    request = requests.get(url, timeout=30, headers=fake_ua_headers)
    soup = BeautifulSoup(request.text, "html.parser")
    deal_link = url + soup.find("section", {"data-test": "mms-home-countdown"}).find(
        "a"
    ).get("href")
    deal_data = BeautifulSoup(
        requests.get(deal_link, timeout=30, headers=fake_ua_headers).text, "html.parser"
    )

    deal.title = (
        deal_data.find("nav", {"aria-label": "Sie sind hier:"}).findAll("a")[-1].text
    )
    deal.subtitle = deal_data.find("h1", {"color": "#111112"}).text
    deal.image = (
        deal_data.find("div", {"class": "pdp-gallery-image"}).find("img").get("src")
    )
    deal.subcategory = "Tagesdeal"
    deal.url = deal_link
    deal.color = "#E20000"
    deal.availability = "100%"
    deal.old_price = (
        deal_data.find("span", {"class": "sc-3f2da4f5-0 hpcVHR"})
        .text.replace("CHF", "")
        .strip()
    )
    deal.new_price = (
        deal_data.find("span", {"class": "sc-3f2da4f5-0 gaNjcA sc-b45c0335-2 fWUVlw"})
        .text.replace("CHF", "")
        .strip()
    )

    return deal


def get_any_deal(deal):
    """
    Return deal by string
    """
    logging.info("Getting deal for %s", deal)
    try:
        if deal == "digitec":
            return get_digitec_deal("https://www.digitec.ch/de/daily-deal")
        if deal == "galaxus":
            return get_digitec_deal("https://www.galaxus.ch/de/daily-deal", "#222")
        if deal == "daydeal_tagesdeal":
            return get_daydealbeta("tagesdeal")
        if deal == "daydeal_it_multimedia":
            return get_daydealbeta("it-multimedia")
        if deal == "daydeal_haushalt_wohnen":
            return get_daydealbeta("haushalt-wohnen")
        if deal == "daydeal_supermarkt_drogerie":
            return get_daydealbeta("supermarkt-drogerie")
        if deal == "daydeal_baumarkt_hobby":
            return get_daydealbeta("baumarkt-hobby")
        if deal == "daydeal_familie_baby":
            return get_daydealbeta("familie-baby")
        if deal == "daydeal_sport_freizeit":
            return get_daydealbeta("sport-freizeit")
        if deal == "daydeal_daily":
            return get_deal("https://www.daydeal.ch/")
        if deal == "daydeal_weekly":
            return get_deal("https://www.daydeal.ch/deal-of-the-week/")
        if deal == "mediamarkt":
            return get_mediamarkt_deal("https://www.mediamarkt.ch")
        if deal == "blick":
            return get_blick_deal("tagesdeal")
        if deal == "blick_weekly":
            return get_blick_deal("wochendeal")
        return {}

    except Exception as ex:
        logging.error("Failed to parse %s", deal)
        logging.error("The following exception was thrown:\n%s", ex)
        return []


logging.basicConfig(
    format="%(levelname)s %(asctime)s - %(message)s", level=logging.INFO
)

deals_list = [
    "digitec",
    "galaxus",
    "daydeal_tagesdeal",
    "daydeal_it_multimedia",
    "daydeal_haushalt_wohnen",
    "daydeal_supermarkt_drogerie",
    "daydeal_familie_baby",
    "daydeal_baumarkt_hobby",
    "daydeal_sport_freizeit",
    "blick",
    "blick_weekly",
    "mediamarkt",
]


def main():
    with Pool(5) as p:
        deals = p.map(get_any_deal, deals_list)

    output = {}
    for i, _ in enumerate(deals_list):
        if not deals[i] == []:
            output[deals_list[i]] = json.loads(str(deals[i]))

    logging.info("Done, writing file")
    filename_date = date.today().strftime("%Y-%m-%d")
    print(json.dumps(output))
    with open("/deals/deals-" + filename_date + ".json", "w", encoding="utf-8") as f:
        f.write(json.dumps(output))


if __name__ == "__main__":
    freeze_support()
    main()
