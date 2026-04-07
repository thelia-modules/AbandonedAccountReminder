{extends file="email-layout.tpl"}

{* Do not provide a "Open in browser" link  *}
{block name="browser"}{/block}
{* No pre-header *}
{block name="pre-header"}{/block}

{* Subject  *}
{block name="email-subject"}{intl l="Welcome to our store !" d="abandonedaccountreminder"}{/block}


{* -- Declare assets directory, relative to template base directory --------- *}
{declare_assets directory='assets'}

{* Set the default translation domain, that will be used by {intl} when the 'd' parameter is not set *}
{default_translation_domain domain='bo.default'}

{* Content  *}
{block name="email-content"}
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-color: #e5e3e3;
            font-family:'Josefin Sans', sans-serif;
        }

        p {
            margin-top: 10px;
            text-align: center;
            margin-bottom: 10px;
        }

        .content {
            background-color: #fff;
            padding: 20px;
            margin: auto;
        }

        .btn-action {
            color: #fff !important;
            background-color: #AF1F4C;
            padding: 10px 20px;
            text-transform: uppercase;
            text-decoration: none;
        }

    </style>
    <table width="100%">
        <tr>
            <td class="content">
                {block name="content"}{/block}

                <br>
                <p><a class="btn-action" href="{url path="/"}">{intl l='Discover our products' d="abandonedaccountreminder"}</a></p>
                <br>
            </td>
        </tr>
    </table>
    <br />
    <p>{intl l='Need help? Our team is here to answer all your questions.' d="abandonedaccountreminder"}</p>
    <p>{intl l='See you soon at the store!' d="abandonedaccountreminder"}</p>
    <br />
    <br />
    {block name="footer"}
    {/block}

{/block}
