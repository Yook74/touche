import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './problems.component.html'
})
export class LiveStandingProblemsComponent implements FieldComponent {
    @Input() data: any;
}
