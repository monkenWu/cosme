function getDataByInfoList<TinfoItem, TdataItem>(
  infoList: TinfoItem[],
  getProcess: (
    infoItem: TinfoItem,
    processResolve: (dataItem:TdataItem) => void
  ) => void,
): Promise<TdataItem[]> {
  return new Promise((resolve) => {
    const tasks: Promise<TdataItem>[] = [];
    // 利用key一一取得預覽圖
    infoList.forEach((infoItem) => {
      tasks.push(
        new Promise((processResolve) => {
          getProcess(infoItem, processResolve);
        }),
      );
    });
    Promise.all(tasks).then((dataList) => {
      resolve(dataList);
    });
  });
}

/*eslint-disable*/
export { getDataByInfoList };
