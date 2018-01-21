import { Component, Input, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';

@Component({
    selector: 'confirm',
    templateUrl: './confirm.component.html'
})
export class ConfirmComponent {

    title: string;
    message: string;

    response: boolean = false;

    constructor(
        public dialogRef: MatDialogRef<ConfirmComponent>,
        @Inject(MAT_DIALOG_DATA) public data: any) {
        this.title = this.data.title;
        this.message = this.data.message;
    }

    onNoClick(): void {
        this.dialogRef.close(this.response);
    }
}
