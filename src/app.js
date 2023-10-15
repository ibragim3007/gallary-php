let drag = document.querySelector(".pole-for-file")
let buttonUpload = document.querySelector(".upload")
let files = document.querySelector(".files")
let pole = document.querySelector(".pole")
let inputFile = document.querySelector(".input__file")

let ImagesForUpload = [];
const types = ['image/jpeg', 'image/png']
let imagesList = document.querySelector(".images")

drag.addEventListener('dragenter', (e) => {
    e.preventDefault()
    drag.classList.add("focus")
})
drag.addEventListener('dragleave', (e) => {
    e.preventDefault()
    drag.classList.remove("focus")
})
drag.addEventListener('dragover', (e) => {
    e.preventDefault()
})

drag.addEventListener('drop', (e) => {
    e.preventDefault()
    drag.classList.remove("focus")
    files.classList.add("filet_add_button")

    const filesInDrop = e.dataTransfer.files
    for(let key in filesInDrop){
        if(!types.includes(filesInDrop[key].type)){
            continue;
        }
        ImagesForUpload.push(filesInDrop[key])
        let imageTempUrl = URL.createObjectURL(filesInDrop[key])
        imagesList.innerHTML += `<img class="upload_file" src="${imageTempUrl}" alt="${imageTempUrl}">`
    }
    buttonUpload.classList.remove("none")
    console.log(ImagesForUpload)
})

inputFile.addEventListener('change', function(e)
{
    console.log(this.files)
    files.classList.add("filet_add_button")

    const filesInDrop = this.files
    for(let key in filesInDrop){
        if(!types.includes(filesInDrop[key].type)){
            continue;
        }
        ImagesForUpload.push(filesInDrop[key])
        let imageTempUrl = URL.createObjectURL(filesInDrop[key])
        imagesList.innerHTML += `<img class="upload_file" src="${imageTempUrl}" alt="${imageTempUrl}">`
    }
    buttonUpload.classList.remove("none")
    console.log(ImagesForUpload)
})

const uploadFiles = () => {
    let formData = new FormData()

    for(let key in ImagesForUpload){
        formData.append(key, ImagesForUpload[key])
    }
    fetch('/core/upload.php', {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            console.log(result)
            if(result.status == false) {return false}

            for(let key in result)
            {
                console.log(result[key].path);
                pole.innerHTML += `
                       <div class="item">
                        <a data-fancybox="gallery" href="${result[key].path}"><img id="myImg" src="${result[key].path}" alt="" class="icon"></a>
                    </div>
                `;
            }

            imagesList.innerHTML = ``
            ImagesForUpload = []
            buttonUpload.classList.remove("focus")

        })
}

