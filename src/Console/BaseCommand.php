<?php

namespace OwenIt\Auditing\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! $this->validateData()) {
            return SymfonyCommand::INVALID;
        }

        return parent::execute($input, $output);
    }

    protected function validateData(): bool
    {
        $validator = Validator::make($this->options(), $this->rules());

        if ($validator->fails()) {
            $this->info('Validation Errors:');
            $errors = $validator->errors()->all();
            foreach ($errors as $error) {
                $this->error($error);
            }

            return false;
        }

        return true;
    }

    /**
     * Validate command inputs just like regular rules method of the requests.
     */
    protected function rules(): array
    {
        return [];
    }
}
