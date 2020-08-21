import '../css/tictactoe.css';

var tictactoe = {};
window.tictactoe = tictactoe;

tictactoe.rootElement = '';
tictactoe.size = 9;
tictactoe.combinations = [];
tictactoe.history = [];

tictactoe.initialState = {
  playerSymbols: ['X', 'O'],
  currentTurn: 0,
  board: Array(tictactoe.size).fill(-1),
  winner: false,
  boardMessage: 'Enjoy Playing',
};

tictactoe.state = {...tictactoe.initialState};

tictactoe.constructor = (element) => {
  tictactoe.rootElement = element;
  tictactoe.combinations = tictactoe.getCombinations();
  tictactoe.render();
};

tictactoe.getSymbol = (index) => {
  let symbol = tictactoe.state.board[index];
  if (symbol === -1) {
    return '';
  }
  return tictactoe.state.playerSymbols[symbol];
};

tictactoe.getBoard = (copy = false) => copy ? JSON.parse(JSON.stringify(tictactoe.state.board)) : tictactoe.state.board;

tictactoe.render = () => {
  let flexString = '<div class=\'board\'>';
  for (let i = 0; i < tictactoe.size; i++) {
    let winnerClass = '';
    if (Array.isArray(tictactoe.state.winner) && tictactoe.state.winner.indexOf(i) > -1) {
      winnerClass = 'sq-won';
    }
    let properties = {
      onclick: 'tictactoe.clickHandler(this)',
    };
    let isSetClass = '';
    if (tictactoe.getSymbol(i) !== '') {
      isSetClass = 'square-is-set';
    }
    flexString += '<div class=\'square ' + isSetClass + winnerClass + '\' id=\'cell-' + i + '\' onclick=\'' + properties.onclick + '\'>';
    flexString += tictactoe.getSymbol(i);
    flexString += '</div>';
  }
  flexString += '</div><div class=\'message\' id=\'winner\'>';
  flexString += tictactoe.state.boardMessage + '</div>';
  if (tictactoe.history.length > 0) {
    flexString += '<div><b>Wins:</b><br />';
    tictactoe.state.playerSymbols.forEach((playerSymbol) => {
      flexString += playerSymbol + ' : ' + (tictactoe.historyOccurrences(playerSymbol) || 0) + '<br />';
    });
    flexString += '</div>';
  }
  document.getElementById(tictactoe.rootElement).innerHTML = flexString;
};

tictactoe.historyOccurrences = (symbol) => (new Map([...new Set(tictactoe.history)].map(
  x => [x, tictactoe.history.filter(y => y === x).length],
))).get(symbol);

tictactoe.clickHandler = (event) => {
  let currentSymbol = tictactoe.state.playerSymbols[tictactoe.state.currentTurn];
  let nextTurn = tictactoe.state.playerSymbols[tictactoe.state.currentTurn === 0 ? 1 : 0];
  if (!tictactoe.state.winner) {
    if (tictactoe.state.board[event.id.split('-')[1]] === -1) {
      tictactoe.state.boardMessage = 'Player ' + nextTurn + '\'s turn!';
      tictactoe.state.board[event.id.split('-')[1]] = tictactoe.state.currentTurn;
      let wins = tictactoe.checkForWinner(tictactoe.state.board);
      if (Array.isArray(wins)) {
        tictactoe.state.boardMessage = 'Player ' + currentSymbol + ' wins!';
        tictactoe.history.push(currentSymbol);
        tictactoe.state.winner = wins;
      }
      tictactoe.state.currentTurn = tictactoe.state.currentTurn === 0 ? 1 : 0;
    }
    if (!(tictactoe.state.winner) && !tictactoe.state.board.some((e) => e === -1)) {
      tictactoe.state.boardMessage = 'There is no winner!';
    }
    tictactoe.render();
    return;
  }
  if (tictactoe.state.winner || !tictactoe.state.board.some((e) => e === -1)) {
    tictactoe.reset();
  }
};

tictactoe.reset = () => {
  tictactoe.combinations = [];
  tictactoe.state = {...tictactoe.initialState};
  tictactoe.state.board = Array(tictactoe.size).fill(-1);
  tictactoe.constructor(tictactoe.rootElement);
};

tictactoe.getCombinations = () => {
  let size = tictactoe.size;
  let width = size / Math.sqrt(size);
  let numOfCombinations = 2 * size + 2;
  var combos = [];
  let hi = 0;
  while (hi < size) {
    let hiArray = [];
    let hitmp = hi;
    hi += width;
    for (let hiItem = hitmp; hiItem < hi; hiItem++) {
      hiArray.push(hiItem);
    }
    combos.push(hiArray);
  }
  let vi = 0;
  while (vi < width) {
    let viArray = [];
    let viItem = vi;
    while (viItem < size) {
      viArray.push(viItem);
      viItem += width;
    }
    vi++;
    combos.push(viArray);
  }
  [0, width - 1].forEach((num) => {
    let diArray = [];
    let diItem = num;
    while (diItem < (num > 0 ? size - 1 : size)) {
      diArray.push(diItem);
      diItem += num > 0 ? width - 1 : width + 1;
    }
    combos.push(diArray);
  });
  return combos;
};

tictactoe.checkForWinner = (board) => {
  return tictactoe.combinations.find((combination) => {
    if (board[combination[0]] !== -1 &&
      board[combination[1]] !== -1 &&
      board[combination[2]] !== -1 &&
      board[combination[0]] === board[combination[1]] &&
      board[combination[1]] === board[combination[2]]) {
      return combination;
    }
    return false;
  });
};

tictactoe.ai = {};

tictactoe.ai.player = true;
tictactoe.ai.choice;

tictactoe.ai.start = () => tictactoe.ai.alphabeta(tictactoe.getBoard(true), 0, true);

tictactoe.ai.getAvailableMoves = (board) => {
  const moves = [];
  board.filter((e, index) => e === -1 && moves.push(index));
  return moves;
};

tictactoe.ai.minimax = (board, depth, player) => {
  let wins = tictactoe.checkForWinner(board);
  if (Array.isArray(wins) || !board.includes(-1)) {
    return tictactoe.ai.getScore(board, player) - depth;
  }
  var score = [];

  moves.forEach((e) => {
    let tmpBoard = board;
    tmpBoard[e] = +player;
    tictactoe.render();
    score.push(tictactoe.ai.minimax(tmpBoard, depth - 1, !player));
  });
  console.log('mv', moves, score);
  if (player === tictactoe.ai.player) {
    let bestIndex = score.reduce((best, e, index, arr) => e > arr[best] ? index : best, 0);
    tictactoe.ai.choice = moves[bestIndex];
    return score[bestIndex] - depth;
  }
  let worstIndex = score.reduce((worst, e, index, arr) => e < arr[worst] ? index : worst, 0);
  tictactoe.ai.choice = moves[worstIndex];
  return score[worstIndex] - depth;
};

tictactoe.ai.alphabeta = (board, depth, player, alpha = Number.NEGATIVE_INFINITY, beta = Number.POSITIVE_INFINITY) => {
  if (Array.isArray(tictactoe.checkForWinner(board))) {
    return tictactoe.ai.getScore(board, player);
  }
  depth++;
  const moves = tictactoe.ai.getAvailableMoves(board);
  let result;
  if (player === false) {
    moves.forEach((e) => {
      result = tictactoe.ai.setMoveOnBoard(board, e, true, depth, alpha, beta);
      if (result > alpha) {
        alpha = result;
        if (depth === 1) {
          tictactoe.ai.choice = move;
        }
      } else if (alpha >= beta) {
        return alpha;
      }
    });
    return alpha;
  }
  moves.forEach((e) => {
    result = tictactoe.ai.setMoveOnBoard(board, e, false, depth, alpha, beta);
    if (result < beta) {
      beta = result;
      if (depth === 1) {
        tictactoe.ai.choice = move;
      }
    } else if (beta <= alpha) {
      return beta;
    }
  });
  return beta;
};

tictactoe.ai.setMoveOnBoard = (board, move, player, depth, alpha, beta) => {
  let tmpBoard = JSON.parse(JSON.stringify(board));
  board[move] = player ? 1 : -1;
  return tictactoe.ai.alphabeta(tmpBoard, depth, alpha, beta);
};

tictactoe.ai.getScore = (board, player, depth) => {
  if (Array.isArray(tictactoe.checkForWinner(board))) {
    return player === tictactoe.ai.player ? (depth - 10) : (10 - depth);
  }
  return 0;
};


(() => tictactoe.constructor('root'))('init');
