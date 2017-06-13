<?php
    
    class Story extends Model {
    
            function __construct() {
                $this->name = 'story';
                $this->idColumnName = "id";

                $this->hasOne("comment", "story_id");
            }



            public function creationForm() {
                $form = new Form("POST");
                $form->label("Název");
                $form->textInput("name", "name", "form-control");
                $form->label("Datum");
                $form->dateInput("date", "date", "form-control");
                $form->label("Text");
                $form->textArea("text", "form-control", "text");
                $form->button("Vytvořit", "button", "btn btn-primary");
                $form->done();

                return $form;
            }
    }
    