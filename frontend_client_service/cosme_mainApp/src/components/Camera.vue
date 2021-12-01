<template>
  <div>
    <video v-show="!isCaptured" ref="player" width="100%" autoplay></video>
    <canvas v-show="isCaptured" ref="snapshot" style="width:100%"></canvas>
    <div>
      <button @click="$emit('cancel')" class="btn btn-outline-secondary">取消</button>
      <button v-show="!isCaptured" @click="doCapture()" class="btn btn-outline-success">拍攝</button>
      <button v-show="isCaptured" @click="isCaptured=false" class="btn btn-outline-secondary">重新拍攝</button>
      <button v-show="isCaptured" @click="captureFinish()" class="btn btn-outline-success">拍攝完成</button>
      <slot name="other-btn"></slot>
    </div>
  </div>
</template>

<script>
/**
 *captureFinishHook:傳遞拍攝完成的圖片至外部
 * */

export default {
  data() {
    return {
      cameraReady: false,
      isCaptured: false,
      player: '',
      snapshotCanvas: '',
      videoTracks: '',
    };
  },
  methods: {
    doCapture() {
      this.isCaptured = true;
      // canvas 畫出的比例是按照錄影機而非video的比例
      this.snapshotCanvas.height = this.snapshotCanvas.width;
      const context = this.snapshotCanvas.getContext('2d');
      // Draw the video frame to the canvas.
      context.drawImage(
        this.player,
        0,
        0,
        this.snapshotCanvas.width,
        this.snapshotCanvas.height,
      );
    },
    captureFinish() {
      const img = this.snapshotCanvas.toDataURL('image/jpeg');
      this.$emit('capture-finish', img);
    },
    closeCamera() {
      this.videoTracks.forEach((track) => {
        track.stop();
      });
    },
  },
  mounted() {
    this.isCaptured = false;
    this.player = this.$refs.player;
    this.snapshotCanvas = this.$refs.snapshot;
    const handleSuccess = (stream) => {
      // 只有剛初始化成功才會呼叫
      if (!this.cameraReady) {
        this.cameraReady = true;
        this.$emit('camera-ready');
      }
      this.player.srcObject = stream;
      this.videoTracks = stream.getVideoTracks();
    };

    navigator.mediaDevices
      .getUserMedia({
        video: {
          width: 480,
          height: 480,
          // aspectRatio: { ideal: 1.7777777778 },
        },
      })
      .then(handleSuccess);
  },
  beforeDestroy() {
    this.closeCamera();
  },
};
</script>
