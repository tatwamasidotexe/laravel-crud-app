<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                width: 100%;
                margin: 0;
                padding: 35px 20px;
                font-family: Arial, sans-serif;
                box-sizing: border-box;
                overflow-x: hidden;
            }
            .my-0 {
                margin-top: 0;
                margin-bottom: 0;
            }
            .row{
                display: flex;
                width: 100vw;
                justify-content: center;
            }
            tr {
                box-sizing: border-box;
            }
            .flex-column{
                flex-direction: column;
            }
            .align-items-end{
                align-items: end;
            }
            .border-bottom td {
                border-bottom: 1px solid black;
            }
            .logoCol {
                width: fit-content;
                /* display: flex; */
                /* align-items: center; */
                /* justify-content: center; */
                padding: 0 20px;
                /* background-color: blue; */
            } 
            .headerTextCol{
                width: 50vw;
                text-align: center;
                padding: 0 20px;
                /* background-color: yellow; */
            }
            .degCertNumCol {
                /* display:flex; */
                /* background-color: cyan; */
                font-size:12px; 
                padding: 10px 10px;
                vertical-align: bottom;
            }
            .certBody {
                align-items: center;
                text-align: center;
                padding-top: 40px;
            }
            .signatures img{
                width: 200px;
                height: auto;
            }
            .signatures h4 {
                margin-top: 0;
            }
        </style>
    </head>
    <body>
        <table style="width: 100%; border-collapse: collapse; border: 10px solid #1d3d70;">
            <tr class="border-bottom">
                <td align ="right">
                    <img width='150px' height='150px' src="{{asset('assets/images/XIM_University_Logo.png') }}" alt="ximLogo">
                </td>
                <td style="text-align: center;">
                    <h1>XIM UNIVERSITY</h1>
                    <p class='my-0 mb-2'>(Established under the Xavier University, Odisha (Amendment) Act 2021)</p>
                    <h3>School of Computer Science and Engineering<br>Bhubaneswar</h3>
                </td>
                <td class="degCertNumCol">
                    <p class="my-0 degreeNum">XIM20240427UCSE200<?php echo $u_id?>UB</p>
                </td>  
            </tr>
            <tr style="width: 100%;">
                <td colspan="3" style="width: 100%; text-align: center; padding-top: 30px;">
                    <p class="my-0"><i>The Governing Board hereby certifies that</i></p>
                    <h1><i>Tatwamasi Mishra<?php //echo $username;?></i></h1>
                    <p class="my-0"><i>
                        Class 2020-2024<br><br>
                        on the successful completion of all the requirements and on the<br>
                        recommendation of the faculty is awarded the Degree of
                    </i></p>
                    <h1><i>B.Tech in Computer Science & Engineering (Hons.)</i></h1>
                    <p class="my-0"><i>
                        with all its rights and privileges.
                    </i></p>
                    <p><i>
                        Given in Bhubaneswar, Odisha, India on 27th April 2024.
                    </i></p>
                </td>
            </tr>
            <tr class="signatures">
                <td align="right" style="text-align: center;">
                    <img src="{{ asset('assets/images/deanSign.png') }}" alt="deanSign.png">
                    <h4>Dean (Academics)</h4>
                </td>
                <td style="text-align: center;">    
                    <img src="{{ asset('assets/images/registrarSign.png') }}" alt="registrarSign.png">
                    <h4>Registrar</h4>
                </td>
                <td style="text-align: center;">
                    <img src="{{ asset('assets/images/VCSign.png') }}" alt="VCSign.png">
                    <h4>Vice-Chancellor</h4>
                </td>
            </tr>
        </table>
    </body>
</html>