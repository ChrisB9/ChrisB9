const SIZE = 3;
const CELLS = SIZE * SIZE;
type Player = 0 | 1;
type Cell = Player | -1;

const LINES: number[][] = (() => {
  const lines: number[][] = [];
  for (let r = 0; r < SIZE; r++) lines.push(Array.from({ length: SIZE }, (_, c) => r * SIZE + c));
  for (let c = 0; c < SIZE; c++) lines.push(Array.from({ length: SIZE }, (_, r) => r * SIZE + c));
  lines.push(Array.from({ length: SIZE }, (_, i) => i * (SIZE + 1)));
  lines.push(Array.from({ length: SIZE }, (_, i) => (i + 1) * (SIZE - 1)));
  return lines;
})();

const SYMBOLS = ['X', 'O'] as const;

interface Copy {
  enjoy: string;
  turn: string;
  wins: string;
  draw: string;
  cell: string;
}

const fill = (template: string, vars: Record<string, string | number>) =>
  template.replace(/\{(\w+)\}/g, (m, k) => (k in vars ? String(vars[k]) : m));

export function mountTicTacToe(root: HTMLElement) {
  const copy: Copy = {
    enjoy: root.dataset.enjoy ?? 'Enjoy playing',
    turn: root.dataset.turn ?? "Player {symbol}'s turn",
    wins: root.dataset.wins ?? 'Player {symbol} wins!',
    draw: root.dataset.draw ?? 'No winner this time.',
    cell: root.dataset.cell ?? 'Cell {n}',
  };

  const board = root.querySelector<HTMLElement>('[data-board]')!;
  const status = root.querySelector<HTMLElement>('[data-status]')!;
  const scores = root.querySelector<HTMLElement>('[data-scores]')!;
  const resetButton = root.querySelector<HTMLButtonElement>('[data-reset]')!;

  let cells: Cell[] = Array<Cell>(CELLS).fill(-1);
  let turn: Player = 0;
  let winning: number[] | null = null;
  let over = false;
  const wins: Record<string, number> = { X: 0, O: 0 };

  const buttons: HTMLButtonElement[] = [];
  for (let i = 0; i < CELLS; i++) {
    const button = document.createElement('button');
    button.type = 'button';
    button.dataset.index = String(i);
    button.className =
      'grid aspect-square place-items-center border border-line bg-surface ' +
      'text-4xl font-semibold transition-colors sm:text-6xl ' +
      'enabled:hover:bg-primary-soft enabled:hover:text-primary disabled:cursor-not-allowed';
    buttons.push(button);
    board.append(button);
  }

  const findWinner = (): number[] | null =>
    LINES.find(
      (line) => cells[line[0]!] !== -1 && line.every((i) => cells[i] === cells[line[0]!]),
    ) ?? null;

  const render = () => {
    buttons.forEach((button, i) => {
      const value = cells[i]!;
      button.textContent = value === -1 ? '' : SYMBOLS[value];
      button.disabled = value !== -1 || over;
      button.setAttribute('aria-label', fill(copy.cell, { n: i + 1 }));
      const won = winning?.includes(i) ?? false;
      button.classList.toggle('bg-primary', won);
      button.classList.toggle('text-white', won);
      button.classList.toggle('!bg-primary', won);
    });
    resetButton.hidden = !over && cells.every((c) => c === -1);
    scores.textContent = `${SYMBOLS[0]} ${wins.X} · ${SYMBOLS[1]} ${wins.O}`;
    scores.hidden = wins.X + wins.O === 0;
  };

  const reset = () => {
    cells = Array<Cell>(CELLS).fill(-1);
    turn = 0;
    winning = null;
    over = false;
    status.textContent = copy.enjoy;
    render();
  };

  const play = (index: number) => {
    if (over || cells[index] !== -1) return;
    const symbol = SYMBOLS[turn]!;
    cells[index] = turn;

    const line = findWinner();
    if (line) {
      winning = line;
      over = true;
      wins[symbol] = (wins[symbol] ?? 0) + 1;
      status.textContent = fill(copy.wins, { symbol });
    } else if (cells.every((c) => c !== -1)) {
      over = true;
      status.textContent = copy.draw;
    } else {
      turn = turn === 0 ? 1 : 0;
      status.textContent = fill(copy.turn, { symbol: SYMBOLS[turn]! });
    }
    render();
  };

  board.addEventListener('click', (event) => {
    const button = (event.target as HTMLElement).closest<HTMLButtonElement>('[data-index]');
    if (button) play(Number(button.dataset.index));
  });

  resetButton.addEventListener('click', reset);
  reset();
}
