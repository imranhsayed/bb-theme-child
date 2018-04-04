{

    if (!Modernizr.objectfit) {

        const objectFit = obj => {

            for (let i = 0; i < obj.length; i++) {
                let image = obj[i].querySelector("img");
                obj[i].style.background = "url('" + image.src + "') center center no-repeat";
                obj[i].classList.add("transparent_child");
            }

        };

        objectFit(document.querySelectorAll(".object-fit-fix"));

        // Jetpack Gallery Support

        const jetpackObjectFit = obj => {

            if (obj) {
                for (let i = 0; i < obj.length; i++) {
                    let image = obj[i].querySelector("img");
                    obj[i].style.background = "url('" + image.src + "') center center no-repeat";
                    obj[i].classList.add("transparent_child");
                }
            }
        }

        jetpackObjectFit(document.querySelectorAll(".tiled-gallery-item"));

    }
}