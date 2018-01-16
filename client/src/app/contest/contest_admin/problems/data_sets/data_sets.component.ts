import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './data_sets.component.html'
})
export class AdminProblemDataSetsComponent implements FieldComponent {
    @Input() data: any;
}
