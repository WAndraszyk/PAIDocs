const errors_field = document.querySelector('#errors')
const nickname_field = document.querySelector('#in')
console.log("dodany")
nickname_field.addEventListener('keyup',(e)=>{
    if(isWhiteSpaceOrEmpty(e.target.value)){
        errors_field.textContent = "Unpermitted value!"
    }else{
        const current_char_count = e.target.value.length
        if (current_char_count < 4){
            errors_field.textContent = "Too short!"
        }else{
            errors_field.textContent = ""
        }
    }
})

function isWhiteSpaceOrEmpty(str) {
    return (/^[\s\t\r\n]+.*$/.test(str) || /^.*[\s\t\r\n]+$/.test(str));
}

