const btn = document.getElementById('btn');
const para = document.getElementById("demo");
const age = document.getElementById("age");
const output = document.getElementById('result');
const btn2 = document.getElementById('btn2');
const main = document.getElementById('main')

function display(){
    para.innerHTML = `<img src="./Assets/jswallpaper.jpg" 
                    alt="deluxe-image" 
                    >`;
    let today = Temporal.ZonedDAteTime.from("2026-07-07T14:00:00[Africa/Kigali]").toPlainTime().toString();
    let div = document.createElement('div');
    div.textContent = `${today}`;
    para.append(div);
}
function disable(){
    para.innerHTML = '';
}
btn.addEventListener("click", display)
btn.addEventListener("dblclick",disable)


btn2.addEventListener('click', function checker(){
    if(age.value>=18){
        output.innerHTML = `Eligible to vote`
    }else{
        output.innerHTML = `Not eligible to vote`;
    }
})

