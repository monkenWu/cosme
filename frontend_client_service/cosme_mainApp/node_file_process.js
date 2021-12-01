/*eslint-disable*/
const node_file_process = () => {

  // 複製檔案
  /**
   * @param { copiedPath: String } (被复制文件的地址，相对地址)
   * @param { resultPath: String } (放置复制文件的地址，相对地址)
   */
  function copyFile(copiedPath, resultPath) {
    copiedPath = path.join(__dirname, copiedPath)
    resultPath = path.join(__dirname, resultPath)

    try {
      //   fs.copyFileSync(copiedPath, resultPath)
      fs.writeFileSync(resultPath, fs.readFileSync(copiedPath))
    } catch (error) {
      console.log(error);
    }
  }

  // 刪除檔案
  /**
   * @param { delPath：String } （需要删除文件的地址）
   * @param { direct：Boolean } （是否需要处理地址）
   */
  function deleteFile(delPath, direct) {
    delPath = direct ? delPath : path.join(__dirname, delPath)
    try {
      /**
       * @des 判断文件或文件夹是否存在
       */
      if (fs.existsSync(delPath)) {
        fs.unlinkSync(delPath);
      } else {
        console.log('inexistence path：', delPath);
      }
    } catch (error) {
      console.log('del error', error);
    }
  }

  // 複製資料夾
  function copyFolder(copiedPath, resultPath, direct) {
    if (!direct) {
      copiedPath = path.join(__dirname, copiedPath)
      resultPath = path.join(__dirname, resultPath)
    }

    function createDir(dirPath) {
      fs.mkdirSync(dirPath)
    }

    if (fs.existsSync(copiedPath)) {
      createDir(resultPath)
      const files = fs.readdirSync(copiedPath, {
        withFileTypes: true
      });
      for (let i = 0; i < files.length; i++) {
        const cf = files[i]
        const ccp = path.join(copiedPath, cf.name)
        const crp = path.join(resultPath, cf.name)
        if (cf.isFile()) {
          /**
           * @des 创建文件,使用流的形式可以读写大文件
           */
          const readStream = fs.createReadStream(ccp)
          const writeStream = fs.createWriteStream(crp)
          readStream.pipe(writeStream)
        } else {
          try {
            /**
             * @des 判断读(R_OK | W_OK)写权限
             */
            fs.accessSync(path.join(crp, '..'), fs.constants.W_OK)
            copyFolder(ccp, crp, true)
          } catch (error) {
            console.log('folder write error:', error);
          }

        }
      }
    } else {
      console.log('do not exist path: ', copiedPath);
    }
  }

  // 刪除文件
  function deleteFile(delPath, direct) {
    delPath = direct ? delPath : path.join(__dirname, delPath)
    try {
      /**
       * @des 判断文件或文件夹是否存在
       */
      if (fs.existsSync(delPath)) {
        fs.unlinkSync(delPath);
      } else {
        console.log('inexistence path：', delPath);
      }
    } catch (error) {
      console.log('del error', error);
    }
  }

  //刪除資料夾
  function deleteFolder(delPath) {
    delPath = path.join(__dirname, delPath)

    try {
      if (fs.existsSync(delPath)) {
        const delFn = function (address) {
          const files = fs.readdirSync(address)
          for (let i = 0; i < files.length; i++) {
            const dirPath = path.join(address, files[i])
            if (fs.statSync(dirPath).isDirectory()) {
              delFn(dirPath)
            } else {
              deleteFile(dirPath, true)
            }
          }
          /**
           * @des 只能删空文件夹
           */
          fs.rmdirSync(address);
        }
        delFn(delPath);
      } else {
        console.log('do not exist: ', delPath);
      }
    } catch (error) {
      console.log('del folder error', error);
    }
  }


  function moveFolder(copiedPath, resultPath, direct){
    copyFolder(copiedPath, resultPath, direct);
    deleteFolder(copiedPath, direct)
  }

  function updateFolder(copiedPath, resultPath, direct){
    deleteFolder(resultPath, direct)
    moveFolder(copiedPath, resultPath, direct);
  }

  return {
    copyFile,deleteFile,copyFolder,deleteFolder,moveFolder,updateFolder
  }

}