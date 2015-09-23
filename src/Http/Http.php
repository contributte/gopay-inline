<?php

namespace Markette\GopayInline\Http;

interface Http
{

    /** Http methods */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * @param Request $request
     * @return Response
     */
    public function doRequest(Request $request);

}
