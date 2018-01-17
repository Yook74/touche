import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './status.component.html'
})
export class JudgeContestDetailStatusComponent implements FieldComponent {
    @Input() data: any;
}
