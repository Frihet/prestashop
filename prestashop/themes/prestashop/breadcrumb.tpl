<!-- Breadcrumb -->
{if isset($smarty.capture.path)}{assign var='path' value=$smarty.capture.path}{/if}
{if $path|regex_replace:"/.*a href.*/":"" == ""}
        <div class="breadcrumb noprint">
	        {$path}
        </div>
{/if}
<!-- /Breadcrumb -->