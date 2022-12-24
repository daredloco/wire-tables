<?php

namespace Helvetiapps\WireTables\Enums;

enum Casts{
    case None;
    case Boolean;
    case Numeric;
    case Currency;
    case Date;
}