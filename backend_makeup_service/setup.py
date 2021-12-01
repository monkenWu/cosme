import argparse

from psgan import get_config

def setup_argparser():
    parser = argparse.ArgumentParser()
    parser.add_argument("--config_file", default="configs/base.yaml", metavar="FILE", help="path to config file")
    parser.add_argument(
        "opts",
        help="Modify config options using the command-line",
        default=None,
        nargs=argparse.REMAINDER,
    )
    return parser

def setup_config():
    config = get_config()
    config.merge_from_file("configs/base.yaml")
    #config.merge_from_list("")
    config.freeze()
    return config
