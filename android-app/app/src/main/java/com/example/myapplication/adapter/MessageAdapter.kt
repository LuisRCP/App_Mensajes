package com.example.myapplication.adapter

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.example.myapplication.R
import com.example.myapplication.model.Message

class MessageAdapter(
    private val messages: List<Message>,
    private val myUserId: Int
) : RecyclerView.Adapter<MessageAdapter.MessageViewHolder>() {

    override fun getItemViewType(position: Int): Int {
        return if (messages[position].emisor_id == myUserId) 1 else 2
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): MessageViewHolder {

        val layout = if (viewType == 1)
            R.layout.message_sent
        else
            R.layout.message_received

        val view = LayoutInflater
            .from(parent.context)
            .inflate(layout, parent, false)

        return MessageViewHolder(view)
    }

    override fun onBindViewHolder(holder: MessageViewHolder, position: Int) {

        val mensaje = messages[position]

        // reset vistas
        holder.textMessage.visibility = View.GONE
        holder.imageMessage.visibility = View.GONE

        // TEXTO
        if (mensaje.tipo == "texto") {

            holder.textMessage.visibility = View.VISIBLE
            holder.textMessage.text = mensaje.mensaje_contenido ?: ""

        }

        // IMAGEN
        else if (mensaje.tipo == "imagen" && mensaje.ruta_archivo != null) {

            holder.imageMessage.visibility = View.VISIBLE

            val url =
                "https://ruxxcluster.online/Mensajes/project-root/" +
                        mensaje.ruta_archivo

            Glide.with(holder.itemView.context)
                .load(url)
                .into(holder.imageMessage)
        }
    }

    override fun getItemCount(): Int {
        return messages.size
    }

    class MessageViewHolder(view: View) : RecyclerView.ViewHolder(view) {

        val textMessage: TextView =
            view.findViewById(R.id.textMessage)

        val imageMessage: ImageView =
            view.findViewById(R.id.imageMessage)
    }
}