import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './judge.component.html'
})
export class JudgeSubmissionJudgeComponent implements FieldComponent {
    @Input() data: any;
}
