import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import AllDeals from './App';
import reportWebVitals from './reportWebVitals';

ReactDOM.render(
  <React.StrictMode>
    <div className="container">
    <a href="https://github.com/herzkadani/alldeals" style={{ position: "absolute", right: 0 }}><img loading="lazy" width="149" height="149" src="https://github.blog/wp-content/uploads/2008/12/forkme_right_green_007200.png?resize=149%2C149" class="attachment-full size-full" alt="Fork me on GitHub" data-recalc-dims="1" /></a>
    <header>
        <h1>AllDeals&nbsp;</h1><h3>Alle Tagesangebote kombiniert!</h3>
    </header>
      <main className="main">
        <AllDeals />
      </main>
      <footer className="footer">
        <a href="https://github.com/herzkadani/alldeals">
          <img src="assets/img/GitHub-Mark-120px-plus.png" alt="view on github" class="github_icon"></img>
        </a>
        <p>made with <span style={{ color: "red" }}>❤</span> in switzerland <br />
          help us improve by contributing on GitHub</p>
      </footer>
    </div>
  </React.StrictMode>,
  document.getElementById('root')
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
