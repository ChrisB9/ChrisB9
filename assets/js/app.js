import '../css/app.scss';
import 'alpinejs';

window.onload = () => {
  if (document.querySelector('.markdown code')) {
    import('highlight.js').then(module => module.initHighlighting());
    import('highlight.js/styles/nord.css');
  }

  if (document.querySelector('.chartjs')) {
    import('chart.js').then((module) => {
      const ctx = document.body.querySelector('.chartjs');
      const ctxContainer = document.body.querySelector('.chartjs-container');
      const chart = new module.Chart(ctx.getContext('2d'), JSON.parse(ctxContainer.dataset['json']));
      if (ctx.hasAttribute('id')) {
        window.charts = window.charts || {};
        window.charts[ctx.getAttribute('id')] = chart;
      }
    });
  }
};
