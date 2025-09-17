<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DynamicForm;
use App\Models\DynamicFormColumn;
use App\Models\DynamicFormValue;

class UpdateFormCommand extends Command
{
    protected $signature = 'form:update {action} {formName} {--data=*}';
    protected $description = 'Update or delete form data dynamically';

    public function handle()
    {
        $action = $this->argument('action');
        $formName = $this->argument('formName');
        $data = $this->option('data');

        try {
            $form = DynamicForm::where('form_name', $formName)->firstOrFail();

            if ($action === 'update') {
                $this->updateForm($form, $data);
            } elseif ($action === 'delete') {
                $this->deleteForm($form);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }

    private function updateForm($form, $data)
    {


        // Parse the data array
        $formData = json_decode($data[0], true);

        \DB::transaction(function () use ($form, $formData) {
            // Update main form
            // Only update form attributes if they are provided and different
            if (isset($formData['form_name']) && $form->form_name !== $formData['form_name']) {
                $form->form_name = $formData['form_name'];
            }

            if (isset($formData['puntajeMaximo']) && $form->puntaje_maximo !== $formData['puntajeMaximo']) {
                $form->puntaje_maximo = $formData['puntajeMaximo'];
            }

            // Save form changes if any
            if ($form->isDirty()) {
                $form->save();
            }

            // Update only specific columns and values if provided
            if (isset($formData['column_name']) && isset($formData['value'])) {
                foreach ($formData['column_name'] as $index => $columnName) {
                    // Find existing column or create new one
                    $column = DynamicFormColumn::firstOrCreate(
                        [
                            'dynamic_form_id' => $form->id,
                            'column_name' => $columnName
                        ]
                    );

                    // Update or create value
                    DynamicFormValue::updateOrCreate(
                        [
                            'dynamic_form_id' => $form->id,
                            'dynamic_form_column_id' => $column->id
                        ],
                        [
                            'value' => $formData['value'][$index]
                        ]
                    );
                }
            }
        });

        $this->info('Form updated successfully');
    }

    private function deleteForm($form)
    {
        \DB::transaction(function () use ($form) {
            // This will cascade delete related columns and values
            $form->delete();
        });
    }
}
