import { Component, Input } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';

import { FieldComponent } from '../../../../components/data_table/field.component';
import { ConfirmComponent } from '../../../../components/confirm/confirm.component';

@Component({
    templateUrl: './delete.component.html'
})
export class AdminProblemDeleteComponent implements FieldComponent {
    @Input() data: any;


    constructor(public dialog: MatDialog) { }

    delete(): void {
        let dialogRef = this.dialog.open(ConfirmComponent, {
            width: '',
            data: { title: 'Delete Problem', message: 'Are you sure?' }
        });

        dialogRef.afterClosed().subscribe(result => {
            console.log('The dialog was closed');
        });
    }
}
