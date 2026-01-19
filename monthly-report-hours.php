<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\AI\Platform\Bridge\Gemini\PlatformFactory;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;

require_once dirname(__DIR__).'/bootstrap.php';

$platform = PlatformFactory::create(env('GEMINI_API_KEY'), http_client());

$messages = new MessageBag(
    Message::forSystem(
        'You are a precise assistant helping users calculate working hours for their monthly reports. ' .
        'Your task is to provide two specific numbers based on the month and year provided by the user: ' .
        '1. Total working hours from the 1st day of the month up to and including the 25th day. ' .
        '2. Total working hours for the entire calendar month. ' .

        'Rules: ' .
        '- A standard workday is 8 hours. ' .
        '- Working days are Monday through Friday. ' .
        '- You must exclude Saturdays, Sundays, and all official public holidays in Poland. ' .
        '- Respond clearly with these two values.'
    ),
    Message::ofUser('Please calculate the hours for Feb 2026'),
);
$result = $platform->invoke('gemini-2.5-flash', $messages);

echo $result->asText().\PHP_EOL;
