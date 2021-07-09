
import React from "react"
import { IconSVGProps } from "./model.td"

const EmbedSound: React.FC<IconSVGProps> = ({ size = 24, color = "currentColor", classes = "" }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className={classes}>
    <path d="M7 4H3C2.44772 4 2 4.44772 2 5V19C2 19.5523 2.44772 20 3 20H7C7.55228 20 8 19.5523 8 19C8 18.4477 7.55228 18 7 18H4.25C4.11193 18 4 17.8881 4 17.75V6.25C4 6.11193 4.11193 6 4.25 6H7C7.55228 6 8 5.55228 8 5C8 4.44772 7.55228 4 7 4Z" fill={color} />
    <path d="M17 18H19.75C19.8881 18 20 17.8881 20 17.75V6.25C20 6.11193 19.8881 6 19.75 6H17C16.4477 6 16 5.55228 16 5C16 4.44772 16.4477 4 17 4H21C21.5523 4 22 4.44772 22 5V19C22 19.5523 21.5523 20 21 20H17C16.4477 20 16 19.5523 16 19C16 18.4477 16.4477 18 17 18Z" fill={color} />
    <path d="M11.3134 10.1784C11.4337 10.1784 11.5501 10.135 11.641 10.0562L13.805 8.17906C14.246 7.79655 15 8.06746 15 8.6084L15 15.3916C15 15.9325 14.246 16.2034 13.805 15.8209L11.641 13.9438C11.5501 13.865 11.4337 13.8216 11.3134 13.8216H8.7C8.3134 13.8216 8 13.5497 8 13.2144V10.7856C8 10.4503 8.3134 10.1784 8.7 10.1784H11.3134Z" fill={color} />
  </svg>
)

export default EmbedSound
