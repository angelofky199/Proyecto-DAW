function get_modal(ref,img,nombre,descripcion,tipo,modo,fenotipo,floracion,produccionint,produccionext,thc,cbd,sabor,efecto) {
    modalProduct.innerHTML =
                '<div id="'+ ref +'" class="container" style="width: 600px; height: 700px;background-image:url(\'./images/fondo.png\');background-size:100% 100%;padding: 0;">' +
                    '<span class="close5" onclick="cerrar()" style="margin-right: 1vw;"><a>&times;</a></span>' +
                    '<div class="row">' +
                        '<div style="background-color: rgba(250,250,250,0.7); width: 80%;  padding-left: 3vw; margin-left: 1vw">' +
                            '<h1 style="font-family: BebasNeue; color: #2d8772;">' + nombre + '</h1>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-lg-push-1 col-lg-10">' +
                            '<p style="text-align: justify; font-family: BrandonReg;">' + descripcion + '</p>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="container col-lg-12" style="padding: auto; margin-top: 4vh;">' +
                            '<div style="background-color: rgba(250,250,250,0.5); height: 41vh">' +
                                '<div class="col-lg-offset-6 col-lg-6">' +
                                    '<table style="margin-top: 1vh; padding: 3px;">' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Tipos de semilla:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg; font-family: BrandonReg;">' + tipo + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Mod. Cultivo*:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + modo + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Fenotipo:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + fenotipo + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Floración:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + floracion + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Producción interior:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + produccionint + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Producción exterior:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + produccionext + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">THC:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + thc + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                        '<td style="font-family: BrandonBold">CBD:</td>' +
                                        '<td style="padding: 3px; font-family: BrandonReg;">' + cbd + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Sabor:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + sabor + '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td style="font-family: BrandonBold">Efecto:</td>' +
                                            '<td style="padding: 3px; font-family: BrandonReg;">' + efecto+ '</td>' +
                                        '</tr>' +
                                    '</table>' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ' +
                        '<div style="position: absolute; margin-left: 2vw; margin-top: 0.5vh; border-style: ridge;border-color: #2d8772;border-width: 4px;">' +
                            '<img src="' + img + '" width="281" height="400">' +
                        '</div>' +
                    '</div>' +
                '</div>';
    modalProduct.style.display = 'block';
}

function cerrar() {
    modalProduct.style.display = "none";
};

$('.flip').hover(function(){
    $(this).find('.card').toggleClass('flipped');

});