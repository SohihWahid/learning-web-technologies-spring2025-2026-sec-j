const style = document.createElement('style');
style.textContent = `
    body { 
        background: #0f172a; color: white; font-family: 'Inter', sans-serif;
        display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;
    }
    #game-container { text-align: center; width: 320px; }
    .score-board { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 1.2rem; }
    .status { margin-bottom: 15px; font-weight: bold; height: 24px; color: #38bdf8; }
    
    .grid { 
        display: grid; grid-template-columns: repeat(3, 1fr); 
        gap: 10px; background: #1e293b; padding: 10px; border-radius: 12px;
    }
    .cell {
        aspect-ratio: 1; background: #334155; border-radius: 8px;
        display: flex; justify-content: center; align-items: center;
        font-size: 2.5rem; font-weight: bold; cursor: pointer; transition: 0.2s;
    }
    .cell:hover { background: #475569; }
    .cell.taken { cursor: not-allowed; }
    
    /* Animations */
    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        80% { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
    }
    .mark-x { color: #f87171; animation: popIn 0.3s ease-out; }
    .mark-o { color: #34d399; animation: popIn 0.3s ease-out; }
    
    .win-highlight { background: #38bdf8 !important; color: #0f172a !important; }

    button {
        margin-top: 25px; padding: 10px 20px; border: none; border-radius: 6px;
        background: #38bdf8; color: #0f172a; font-weight: bold; cursor: pointer;
    }
    button:hover { background: #7dd3fc; }
`;
document.head.appendChild(style);

let board = Array(9).fill(null);
let currentPlayer = 'X';
let gameActive = true;
let scores = { X: 0, O: 0 };

const winConditions = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8], 
    [0, 3, 6], [1, 4, 7], [2, 5, 8], 
    [0, 4, 8], [2, 4, 6]             
];

const root = document.getElementById('game-root');
const container = document.createElement('div');
container.id = 'game-container';

const scoreBoard = document.createElement('div');
scoreBoard.className = 'score-board';
scoreBoard.innerHTML = `<span>Player X: <b id="scoreX">0</b></span><span>Player O: <b id="scoreO">0</b></span>`;
container.appendChild(scoreBoard);

const statusMsg = document.createElement('div');
statusMsg.className = 'status';
statusMsg.innerText = "Player X's Turn";
container.appendChild(statusMsg);

const grid = document.createElement('div');
grid.className = 'grid';
const cells = [];

for (let i = 0; i < 9; i++) {
    const cell = document.createElement('div');
    cell.className = 'cell';
    cell.dataset.index = i;
    cell.onclick = () => handleCellClick(cell, i);
    grid.appendChild(cell);
    cells.push(cell);
}
container.appendChild(grid);

const resetBtn = document.createElement('button');
resetBtn.innerText = "Reset Game";
resetBtn.onclick = resetGame;
container.appendChild(resetBtn);

root.appendChild(container);

function handleCellClick(cell, index) {
    if (!gameActive || board[index]) return;

    board[index] = currentPlayer;
    cell.innerText = currentPlayer;
    cell.classList.add(currentPlayer === 'X' ? 'mark-x' : 'mark-o', 'taken');

    checkResult();
}

function checkResult() {
    let roundWon = false;

    for (let condition of winConditions) {
        const [a, b, c] = condition;
        if (board[a] && board[a] === board[b] && board[a] === board[c]) {
            roundWon = true;
            highlightWinner(condition);
            break;
        }
    }

    if (roundWon) {
        statusMsg.innerText = `Player ${currentPlayer} Wins!`;
        scores[currentPlayer]++;
        document.getElementById(`score${currentPlayer}`).innerText = scores[currentPlayer];
        gameActive = false;
        return;
    }

    if (!board.includes(null)) {
        statusMsg.innerText = "It's a Draw!";
        gameActive = false;
        return;
    }

    currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
    statusMsg.innerText = `Player ${currentPlayer}'s Turn`;
}

function highlightWinner(indices) {
    indices.forEach(i => cells[i].classList.add('win-highlight'));
}

function resetGame() {
    board = Array(9).fill(null);
    currentPlayer = 'X';
    gameActive = true;
    statusMsg.innerText = "Player X's Turn";
    cells.forEach(cell => {
        cell.innerText = '';
        cell.className = 'cell';
    });
}