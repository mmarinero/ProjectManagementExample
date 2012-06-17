<!DOCTYPE html>
    <html>
    <head>
        {block name=head}{/block}
    </head>
    <body>
        <div id="maincontainer">
            <div id="header"><img src="{"images/header.png"|base_url}"></div>
            <div id="nav">
                {block name=nav}{/block}
            </div>
            <div id="content">
                {block name=content}{/block}
            </div>
            <div id="sidebar">
                {block name=sidebar}{/block}
            </div>
            <div id="footer">
                <span>Planificación y gestión de procesos 2012. Setepros</span><span style="float:right">Grupo 10: Mario Marinero Domingo</span>
            </div>
        </div>
    </body>
</html>
