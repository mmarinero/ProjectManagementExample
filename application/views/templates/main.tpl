<!DOCTYPE html>
    <html>
    <head>
        {block name=head}{/block}
    </head>
    <body>
        <div id="maincontainer">
            <div id="header"><img src="{"images/header"|base_url}"></div>
            <div id="nav">
                {block name=nav}{/block}
            </div>
            <div id="main">
                {block name=content}{/block}
            </div>
            <div id="sidebar">
                {block name=sidebar}{/block}
            </div>
            <div id="footer">
                foot
            </div>
        </div>
    </body>
</html>
