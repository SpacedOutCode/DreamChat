const background = document.getElementById('body');
const Colors =  ["linear-gradient(179deg, #201B30 1.01%, #3C247E 99.01%) no-repeat","linear-gradient(179deg, #301B1B 0%, #7E2424 100%) no-repeat","linear-gradient(179deg, #30221B 0%, #7E4524 100%) no-repeat","linear-gradient(179deg, #0A2211 0%, #2C7E24 100%) no-repeat","linear-gradient(179deg, #0A0D22 0%, #243E7E 100%) no-repeat","linear-gradient(179deg, #212123 0%, #3C3C3C 100%) no-repeat"];  
checkTheme();

function checkTheme() {
    var cookies = document.cookie,
        cookies = document.cookie.split(';'),
        cookie = cookies[1],
        value = cookie.split('=')[1];
    background.style.background = Colors[value]
}
function changeTheme(theme) {
    background.style.background = Colors[theme];
    document.cookie = `theme=${theme}`;
    console.log(document.cookie);
}   

function toggleTheme() {
    const themeDiv = document.getElementById('Themes');
    if(themeDiv.style.display == '') {
        themeDiv.style.display = 'flex';
    } else if(themeDiv.style.display == 'flex') {
        themeDiv.style.display = '';
    }
}

function srcEmoji() {
    
}

function srcImg() {
    
}

function srcCode() {
    const codeDiv = document.getElementById('codeInsert');
    if(codeDiv.style.display == '') {
        codeDiv.style.display = 'flex';
    } else if(codeDiv.style.display == 'flex') {
        codeDiv.style.display = '';
    }
}

