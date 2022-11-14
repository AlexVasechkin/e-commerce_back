<?php

namespace App\Domain\Contracts;

use App\Domain\Contracts\Base;

interface VendorInterface extends Base\GetIntIdInterface,
                                  Base\GetSetCodeInterface,
                                  Base\GetSetNameInterface
{}
