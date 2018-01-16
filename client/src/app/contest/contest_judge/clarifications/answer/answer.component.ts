import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './answer.component.html'
})
export class JudgeClarificationAnswerComponent implements FieldComponent {
    @Input() data: any;
}
