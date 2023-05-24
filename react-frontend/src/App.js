import React, { useState, useEffect } from 'react';

function timeElapsedString (timestamp) {
  const now = new Date();
  const then = new Date(timestamp);
  const diff = now - then;
  const diffMinutes = Math.round(diff / 60000);
  // text for the time elapsed
  let output = `vor ${diffMinutes} Minuten`;
  return output;
}
  
  
function AllDeals() {
  const [deals, setDeals] = useState([]);

  useEffect(() => {
    fetch('https://deals.gk.wtf/api.php')
      .then(response => response.json())
      .then(data => {
        if (data) {
          // flatten the structure to have an array with an element for each deal
          // every key is the site name and contains an object with the deal data
          // we want to have an array with an element for each deal

          data = Object.keys(data).map(site => {
            // add the site name to the deal data
            data[site].site = site;
            // if availability is 0, the deal is expired
            data[site].isExpired = data[site].availability === '0' ? 'expired' : '';
            return data[site];
          });
          console.log(data);
          setDeals(data);
        }
      });
  }, []);

  // define the brand colors as in the old brandColors.js
  const brandColors = {
    "digitec": "#005598",
    "daydeal_daily": "#3FAA35",
    "daydeal_weekly": "#3FAA35",
    "blick": "#E20000",
    "blick_weekly": "#E20000",
    "galaxus": "#222",
    "mediamarkt": "#E20000",
    "zmin": "#004daa",
    "zmin_weekly": "#004daa"
  }
  


  return (
<div className="deals_wrapper">
  {deals.map(deal => (
    <div className={`deal_badge ${deal.isExpired}`}>
    <div className="progress" data-label={deal.availability} style={{ borderColor: brandColors[deal.site], textShadow: '-1px -1px 0 ' + brandColors[deal.site] + ', 0 -1px 0 ' + brandColors[deal.site] + ', 1px -1px 0 ' + brandColors[deal.site] + ', 1px 0 0 ' + brandColors[deal.site] + ', 1px 1px 0 ' + brandColors[deal.site] + ', 0 1px 0 ' + brandColors[deal.site] + ', -1px 1px 0 ' + brandColors[deal.site] + ', -1px 0 0 ' + brandColors[deal.site] }}>
      <span className="value" style={{ width: deal.availability, backgroundColor: brandColors[deal.site] }}></span>
    </div>
    <div className="badge_content">
      <div className="badge_header">
        <div>
          <h1 className="title">{deal.title}</h1>
          <h2 className="subtitle">{deal.subtitle}</h2>
        </div>
        <img src={`assets/img/${deal.site}.jpg`} alt={`${deal.site} logo`} />
      </div>
      <img src={deal.image} className="deal_img" />
      <span className="last_update">Letztes Update: {timeElapsedString(deal.timestamp)}</span>
      <div className="badge_footer">
        <div className="prices">
          <h1 className="new_price">CHF {deal.new_price}</h1>
          <h2 className="old_price">CHF {deal.old_price}</h2>
        </div>
        <div>
          <a href={deal.url} className="view_btn_anchor" target="_blank">
            <div className="view_btn" style={{ backgroundColor: brandColors[deal.site] }}>Ansehen</div>
          </a>
        </div>
      </div>
    </div>
  </div>
  ))}
</div>
  );
}

export default AllDeals;