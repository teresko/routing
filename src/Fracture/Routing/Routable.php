<?php

namespace Fracture\Routing;

interface Routable
{
    public function getUri();
    public function setParameters(array $parameters);
}
