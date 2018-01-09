import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Clarification } from '../../models/clarification';

@Injectable()
export class ClarificationService {
    constructor(private baseService: BaseService) { }

    getAnsweredClarifications() {
        return this.baseService.get('');
    }

    getUnansweredClarifications() {
        return this.baseService.get('');
    }

    createClarificationRequest(clarification: Clarification) {
        return this.baseService.post('', clarification);
    }

    answerClarification(clarification: Clarification) {
        return this.baseService.put('', clarification);
    }
}