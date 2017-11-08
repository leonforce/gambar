@extends('layouts.app2')
@section('content')
<!--suppress ALL -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Image v 2.0</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif
                        <form enctype="multipart/form-data" action="{{route('save')}}" method="post" id="form">
                            {{csrf_field()}}
                            <input id="photo" type="file" name="photo" onchange="previewFile()">
                            <input type="text" id="kualitas" readonly hidden name="kualitas" placeholder="Asal" value="">
                            <br>
                            <input id="simpan" type="submit" style="visibility: hidden;" class="btn btn-primary btn-sm" value="Simpan">
                        </form>
                        <br><input type="button" value="Cek Kualitas" onclick="kampret()" ><h1 id="code"></h1>
                        </div>
                </div>
                <div class="panel panel-primary">
                            <div class="panel-heading">Preview
                            </div>
                            <div class="panel-body">
                                <img src="" class="img-responsive" alt="Image preview...">
                            </div>
                        </div>
                <div id="kualitaspreview" class="panel panel-primary" style="visibility: hidden;" >
                    <div class="panel-heading">Kualitas
                            </div>
                            <div class="panel-body">
                                <canvas class="img-responsive" id="myCanvas"></canvas>
                            </div>
                        </div>


                        <script>
                            var reader  = new FileReader();
                            function previewFile() {
                                var preview = document.querySelector('img');
                                var file    = document.querySelector('input[type=file]').files[0];
                                reader.addEventListener("load", function () {
                                    preview.src = reader.result;
                                }, false);

                                if (file) {
                                    reader.readAsDataURL(file);
                                }

                            }
                            function kampret () {
                                var imageObj = new Image();
                                imageObj.src = reader.result;
                                imageObj.onload = function () {
                                    document.getElementById('myCanvas').width = imageObj.width;
                                    document.getElementById('myCanvas').height = imageObj.height;
                                    document.getElementById('simpan').style = "";
                                    document.getElementById('kualitaspreview').style = "";
                                    drawImage(this)
                                };
                            }
                            function drawImage(imageObj) {
                                var codepanel = document.getElementById('code');
                                var canvas = document.getElementById('myCanvas');
                                var context = canvas.getContext('2d');
                                var x = 0;
                                var y = 0;
                                var g;
                                var pixels = []; //my addition
                                var pixel = 0; //my addition
                                context.drawImage(imageObj, x, y);
                                var imageData = context.getImageData(x, y, imageObj.width, imageObj.height);
                                var data = imageData.data;
                                for(var i = 0; i < data.length; i += 4) {
                                    // grayscale
                                    var brightness;
                                    brightness = 0.34 * data[i] + 0.5 * data[i + 1] + 0.16 * data[i + 2];
                                    // red
                                    data[i] = brightness;
                                    // green
                                    data[i + 1] = brightness;
                                    // blue
                                    data[i + 2] = brightness;
                                    if (brightness < 90) {
                                        g = "high";
                                    }
                                    else if ( (brightness > 90 ) && (brightness < 190)  ){
                                        g = "low";
                                    }
                                    else if ( brightness > 190 ){
                                        g = "lain";
                                    }
                                    pixels[pixel] = g; //my addition
                                    pixel++;
                                    var count = 0;
                                    var count3 = 0;
                                    var count4 = 0;
                                }
                                // count R>G>B
                                for(var i = 0; i < pixels.length; ++i){
                                    if(pixels[i] == "high")
                                    {
                                        count++;
                                    }
                                    else if(pixels[i] == "low")
                                    {
                                        count3++;
                                    }
                                    else if(pixels[i] == "lain")
                                    {
                                        count4++;
                                    }

                                }
                                // overwrite
                                context.putImageData(imageData, x, y);
                                // stat
                                var cal = ((count / (count +  count3)) * 100).toFixed(1)  ;
                                var cal3 = ((count3 / (count + count3)) * 100).toFixed(1)  ;
                                if(cal > 30 )
                                {
                                    var stat = "High";
                                }if(cal > 50 && cal3 > 30 )
                                {
                                    var stat = "Medium";
                                } else if(cal3 > 25 )
                                {
                                    var stat = "Low";
                                }
                                codepanel.innerHTML =  "Kualitas : "+stat+ " " ;
                                document.getElementById('kualitas').value = stat;
                            }
                        </script>

                </div>

@endsection
