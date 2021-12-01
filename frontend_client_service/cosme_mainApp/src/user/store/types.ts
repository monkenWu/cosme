/**
 * 讓TS認定，不同組件的action可以互相呼叫
 */

/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { ActionContext } from 'vuex';
import { Mutations as Photo } from './modules/photo/mutations';
import { Mutations as Makeup } from './modules/makeup/mutations';
import { Mutations as User } from './modules/user/mutations';
import { Mutations as History } from './modules/history/mutations';

export interface RootState {
  version: string;
}

export type MutationsTypes = Photo & Makeup & User & History;

export type ExtendActionContext = {
  commit<K extends keyof MutationsTypes>(
    key: K,
    payload?: Parameters<MutationsTypes[K]>[1]
  ): ReturnType<MutationsTypes[K]>;
};
