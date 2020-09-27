import '../css/app.scss';

document.addEventListener('DOMContentLoaded', (event) => {
  if (document.querySelector('.markdown code')) {
    const styles = document.createElement('link');
    styles.rel = 'stylesheet';
    styles.href = '//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/styles/nord.min.css';
    const script = document.createElement('script');
    script.src = '//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/highlight.min.js';
    script.onload = function () {
      if (hljs) {
        hljs.initHighlighting();
      }
    };

    document.head.appendChild(styles);
    document.body.appendChild(script);
  }

  if (document.querySelector('.chartjs')) {
    var script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@2.8.0';
    script.onload = function () {
      const ctx = document.body.querySelector('.chartjs');
      const ctxContainer = document.body.querySelector('.chartjs-container');
      const chart = new Chart(ctx.getContext('2d'), JSON.parse(ctxContainer.dataset['json']));
      if (ctx.hasAttribute('id')) {
        window.charts = window.charts || {};
        window.charts[ctx.getAttribute('id')] = chart;
      }
    };

    document.body.appendChild(script);
  }
});


