import localService from 'localforage';

localService.config({
  driver: [localService.WEBSQL, localService.INDEXEDDB, localService.LOCALSTORAGE],
  name: 'cosme_mainApp',
  version: 0.5,
  size: 4980736, // Size of database, in bytes. WebSQL-only for now.
  storeName: 'keyvaluepairs', // Should be alphanumeric, with underscores.
  description: 'some description',
});

// window.localService = localService;

export default localService;
