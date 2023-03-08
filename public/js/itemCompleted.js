const checkbox = document.getElementById('item_checked')

checkbox.addEventListener('click', (event) =>{

    let checked = event.target.checked

    if(event.target.checked){
        let checked = true
    }
    else{
        let checked = false
    }

    console.log(checked)

    //  Ajax Request Here

})