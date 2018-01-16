import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Clarification } from '../../models/clarification';

const mockClarifications: Clarification[] = [
    {
        id: 1,
        teamId: 1,
        problemId: 1,
        submitTimestamp: '12:00:51',
        replyTimestamp: '15:23:21',
        question: 'What is the question?',
        response: 'This is a good response.',
        broadcast: false
    },
    {
        id: 2,
        teamId: 2,
        problemId: 2,
        submitTimestamp: '12:00:51',
        replyTimestamp: '15:23:21',
        question: 'What is the question?',
        response: 'This is a good response.',
        broadcast: false
    },
    {
        id: 3,
        teamId: 3,
        problemId: 3,
        submitTimestamp: '12:00:51',
        replyTimestamp: '15:23:21',
        question: 'What is the question?',
        response: 'This is a good response.',
        broadcast: false
    }
]

@Injectable()
export class ClarificationService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockClarifications;
    }

    getAnsweredClarifications() {
        return this.baseService.get('');
    }
    getUnansweredClarifications() {
        return this.baseService.get('');
    }

    createClarification(clarification: Clarification) {
        return this.baseService.post('', clarification);
    }

    updateClarification(clarification: Clarification) {
        return this.baseService.put('', clarification);
    }

    requestClarification(clarification: Clarification) {
        return this.baseService.post('', clarification);
    }
    answerClarification(clarification: Clarification) {
        return this.baseService.put('', clarification);
    }

    deleteClarification(clarificationId: number) {
        return this.baseService.delete('');
    }
}
