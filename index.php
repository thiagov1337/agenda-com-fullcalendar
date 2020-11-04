<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <link href='core/main.min.css' rel='stylesheet'/>
    <link href='daygrid/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">

    <script src='core/main.min.js'></script>
    <script src='interaction/main.min.js'></script>
    <script src='daygrid/main.min.js'></script>
    <script src="core/locales/pt-br.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'pt-br',
                plugins: [ 'interaction', 'dayGrid'],
                selectable: true,
                height: 'parent',
                editable: false,
                eventLimit: true, // allow "more" link when too many events
                events: 'list_events.php',
                eventClick: function(info) {
                    //$("#deleleEvent").attr("href", "http://localhost/agendamento/delete.php?id="+info.event.id),
                    info.jsEvent.preventDefault(), // don't let the browser navigate
                    $('#modalVisu #id').text(info.event.id),
                    $('#modalVisu #title').text(info.event.title),
                    $('#modalVisu #start').text(info.event.start.toLocaleString()),
                    $('#modalVisu #end').text(info.event.end.toLocaleString()),
                    $('#modalVisu #usuario').text(info.event.extendedProps.usuario),

                    console.log(info.event.backgroundColor)
                    switch(info.event.backgroundColor) {
                        case "#ff5733":
                            $('#modalVisu #color').text("sala1");
                            break;
                        case "#5DADE2":
                            $('#modalVisu #color').text("Sala2");
                            break;
                        case "#F4D03F":
                            $('#modalVisu #color').text("Sala3");
                            break;
                        case "#C39BD3":
                            $('#modalVisu #color').text("Sala4");
                            break;
                    }

                    $('#modalVisu').modal('show')

                },

                select: function(info) {
                    let dateStr = info.start.toLocaleString();
                    let dateStrConv = dateStr.replace("00:00:00", "");
                    $('#cadastroEvento #start').val(dateStrConv)
                    $('#cadastroEvento #end').val(dateStrConv)
                    $('#cadastroEvento').modal('show')
                }
            });
            calendar.render();
        });

        function DataHora(evento, objeto){
            var keypress=(window.event)?event.keyCode:evento.which;
            campo = eval (objeto);
            if (campo.value == '00/00/0000 00:00:00')
            {
                campo.value=""
            }

            caracteres = '0123456789';
            separacao1 = '/';
            separacao2 = ' ';
            separacao3 = ':';
            conjunto1 = 2;
            conjunto2 = 5;
            conjunto3 = 10;
            conjunto4 = 13;
            conjunto5 = 16;
            if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < (19))
            {
                if (campo.value.length == conjunto1 )
                    campo.value = campo.value + separacao1;
                else if (campo.value.length == conjunto2)
                    campo.value = campo.value + separacao1;
                else if (campo.value.length == conjunto3)
                    campo.value = campo.value + separacao2;
                else if (campo.value.length == conjunto4)
                    campo.value = campo.value + separacao3;
                else if (campo.value.length == conjunto5)
                    campo.value = campo.value + separacao3;
            }
            else
                event.returnValue = false;
        }

        $(document).ready(function(){
            $("#AddEvento").on("submit", function(event){
                event.preventDefault()
                $.ajax({
                    method: 'POST',
                    url: 'cadastrar.php',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function (ret){
                        if(ret['sit']){
                            //$("#msg-cad").html(ret['msg']);
                            location.reload()
                        }else{
                            $("#msg-cad").html(ret['msg']);
                        }
                    }
                })
            })
        })
    </script>
    <style>

        html, body {
            overflow: hidden; /* don't do scrollbars */
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar-container {
            position: fixed;
            top: 50px;
            left: 100px;
            right: 100px;
            bottom: 50px;

        }

    </style>
</head>
<body>
</body>
<?php
    if(isset($_SESSION['msg'])){
        echo ($_SESSION['msg']);
        unset($_SESSION['msg']);
    }
?>
<div id='calendar-container'>
    <div id='calendar'></div>
</div>

<!-- ModalVisual -->
<div class="modal fade" tabindex="-1" id="modalVisu" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhe agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row ">
                    <dt class="col-sm-3">Id evento</dt>
                    <div class="col-sm-9" id="id"></div>

                    <dt class="col-sm-3">Titulo</dt>
                    <div class="col-sm-9" id="title"></div>

                    <dt class="col-sm-3">Incio</dt>
                    <div class="col-sm-9" id="start"></div>

                    <dt class="col-sm-3">Fim</dt>
                    <div class="col-sm-9" id="end"></div>

                    <dt class="col-sm-3">Usuario</dt>
                    <div class="col-sm-9" id="usuario"></div>

                    <dt class="col-sm-3">Sala</dt>
                    <div class="col-sm-9" id="color"></div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">fechar</button>
                <a class="btn btn-secondary" href="" id='deleleEvent'>Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- cadastroEvento -->
<div class="modal fade" id="cadastroEvento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="msg-cad"></span>
                <form id="AddEvento" method="POST">

                    <div class="form-group row">
                        <label class="col-sm-3">usuario</label>
                        <div class="col-sm-9">
                            <input type="text" name="usuario" class="form-control" id="usuario" placeholder="usuario">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3">Titulo</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" id="title" placeholder="Título do evento">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Cor</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="color" name="color">
                                <option value="#FF5733">sala 1</option>
                                <option value="#5DADE2">sala 2</option>
                                <option value="#F4D03F">sala 3</option>
                                <option value="#C39BD3">sala 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Início do evento</label>
                        <div class="col-sm-9">
                            <input type="text" name="start" class="form-control" id="start" placeholder=""  onkeypress="DataHora(event, this)">
                            <small class="form-text text-muted">Exemplo: 01/01/2020 13:30:00</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Final do evento</label>
                        <div class="col-sm-9">
                            <input type="text" name="end" class="form-control" id="end" placeholder="" onkeypress="DataHora(event, this)">
                            <small class="form-text text-muted">Exemplo: 01/01/2020 14:30:00</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" name="CadEvento" id="CadEvento" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
