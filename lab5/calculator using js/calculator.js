const style = document.createElement('style');
style.innerHTML = `
    body {
        background: #121212;
        display:flex; justify-content: center; align-items: center;
        height: 100vh; margin:0; font-family: 'Segoe UI', sans-serif;
    }
    .calc-box{
        background: #1e1e1e; padding:20px; border-radious:20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5); width:300px;
    }
    #screen{
        width:100%; height:60px; background: none; border: none;
        color:#fff; text-align: right; font-size: 2.5rem; mergin-bottom:20px;
        outline: none; pointer-events: none;
    }
    .btn-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    button { 
        height: 60px; border-radius: 12px; border: none; cursor: pointer; 
        font-size: 1.2rem; font-weight: 600; transition: 0.2s; background: #333; color: white;
    }
    button:hover { background: #444; transform: scale(0.95); }
    .op-btn { background: #ff9f0a; color: white; }
    .op-btn:hover { background: #ffb340; }
    .special-btn { background: #a5a5a5; color: black; }
    .wide-btn { grid-column: span 2; }
`;
document.head.appendChild(style);
const app = document.getElementById('app');
const calcBox = document.createElement('div');
calcBox.className = 'calc-box';

const screen = document.createElement('input');
screen.id = 'screen';
screen.value = '0';
calcBox.appendChild(screen);

const btnGrid = document.createElement('div');
btnGrid.className = 'btn-grid';
calcBox.appendChild(btnGrid);
app.appendChild(calcBox);

const button = [
    {label: 'AC', type: 'special'},{label:'DEL', type:'special'},{label:'%', type: 'op'},{label:'/',type:'op'},
    {label: '7'},{label: '8'},{label: '9'},{label: '+', type:'op'},
    {label: '4'},{label: '5'},{label: '6'},{label: '-',type:'op'},
    {label: '1'},{label: '2'},{label: '3'},{label: '*',type:'op'},
    {label: '0',wide:true},{label:'.'},{label: '=',type:'op'},
];
let currentExpression = "";
buttons.forEach(btnData =>{
    const btn = document.createElement('button');
    btn.innerText = btnData.label;

    if (btnData.type === 'op') btn.classList.add('op-btn');
    if (btnData.type === 'special') btn.classList.add('special-btn');
    if (btnData.wide) btn.classList.add('wide-btn');

    btn.onclick = () => {
        handlePress(btnData.label)
    };
    btnGrid.appendChild(btn);
});
function handlePress(value){
    if(value==='AC'){
        currentExpresion = "";
        screen.value="0";
    }
    else if (value === 'DEL'){
        currentExpression = currentExpression.slice(0, -1);
        screen.value = currentExpression || "0";
    }
    else if(value === '='){
        try{
            const result = new Function(`return ${currentExpression}`)();
            screen.value = result;
            currentExpression = result.toString();
        }
        catch{
            screen.value="Error";
            currentExpression = "";
        }
    }
    else{
        if(currentExpression === "" && isNaN(value)) return;
        currentExpression += value;
        screen.value = currentExpression;
    }
}